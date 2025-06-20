<?php
require_once 'util_config.php';

header('Content-Type: application/json');

function handle_webhook($conn) {
    $raw_post_data = file_get_contents('php://input');
    $webhook_event = json_decode($raw_post_data, true);

    file_put_contents('webhook_log.txt', print_r($webhook_event, true) . "\n", FILE_APPEND);

    $event_type = $webhook_event['event_type'] ?? '';
    $subscription_id = $webhook_event['resource']['id'] ?? '';

    if (!$subscription_id) {
        file_put_contents('debug.log', "Webhook error: Missing subscription_id\n", FILE_APPEND);
        return ['status' => 'error', 'message' => 'Missing subscription_id'];
    }

    switch ($event_type) {
        case 'PAYMENT.SALE.COMPLETED':
            $paypal_transaction_id = $webhook_event['resource']['id'];
            $amount = $webhook_event['resource']['amount']['total'];
            $currency = $webhook_event['resource']['amount']['currency'];
            $transaction_date = $webhook_event['resource']['create_time'];
            $status = 'COMPLETED';
            $transaction_type = 'PAYMENT';

            // Insert transaction
            $query = "
                INSERT INTO transactions (
                    subscription_id, paypal_transaction_id, amount, currency, status,
                    transaction_type, transaction_date
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ";
            $stmt = $conn->prepare($query);
            $stmt->bind_param(
                'ssdssss',
                $subscription_id,
                $paypal_transaction_id,
                $amount,
                $currency,
                $status,
                $transaction_type,
                $transaction_date
            );
            if (!$stmt->execute()) {
                file_put_contents('debug.log', "Transaction insert error: " . $stmt->error . "\n", FILE_APPEND);
                return ['status' => 'error', 'message' => 'Failed to save transaction'];
            }
            $stmt->close();

            // Update next_billing_date
            $next_billing_date = date('Y-m-d H:i:s', strtotime($transaction_date . ' +1 month'));
            $query = "
                UPDATE subscriptions
                SET next_billing_date = ?, status = 'ACTIVE', updated_at = NOW()
                WHERE subscription_id = ?
            ";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ss', $next_billing_date, $subscription_id);
            if (!$stmt->execute()) {
                file_put_contents('debug.log', "Subscription update error: " . $stmt->error . "\n", FILE_APPEND);
                return ['status' => 'error', 'message' => 'Failed to update subscription'];
            }
            $stmt->close();
            break;

        case 'BILLING.SUBSCRIPTION.CANCELLED':
        case 'BILLING.SUBSCRIPTION.EXPIRED':
        case 'BILLING.SUBSCRIPTION.SUSPENDED':
            $status = $webhook_event['resource']['status'];
            $query = "
                UPDATE subscriptions
                SET status = ?, next_billing_date = NULL, updated_at = NOW()
                WHERE subscription_id = ?
            ";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ss', $status, $subscription_id);
            if (!$stmt->execute()) {
                file_put_contents('debug.log', "Subscription update error: " . $stmt->error . "\n", FILE_APPEND);
                return ['status' => 'error', 'message' => 'Failed to update subscription'];
            }
            $stmt->close();
            break;

        case 'BILLING.SUBSCRIPTION.ACTIVATED':
        case 'BILLING.SUBSCRIPTION.RE-ACTIVATED':
            $status = $webhook_event['resource']['status'];
            $next_billing_date = $webhook_event['resource']['billing_info']['next_billing_time'] ?? null;
            $query = "
                UPDATE subscriptions
                SET status = ?, next_billing_date = ?, updated_at = NOW()
                WHERE subscription_id = ?
            ";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sss', $status, $next_billing_date, $subscription_id);
            if (!$stmt->execute()) {
                file_put_contents('debug.log', "Subscription update error: " . $stmt->error . "\n", FILE_APPEND);
                return ['status' => 'error', 'message' => 'Failed to update subscription'];
            }
            $stmt->close();
            break;
    }

    file_put_contents('debug.log', "Webhook success: Event $event_type processed for subscription $subscription_id\n", FILE_APPEND);
    return ['status' => 'success', 'message' => 'Webhook processed'];
}

$result = handle_webhook($conn);
echo json_encode($result);
?>