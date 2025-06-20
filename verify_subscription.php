<?php
require_once 'util_config.php';
require_once 'util_session.php';

header('Content-Type: application/json');

function verify_subscription($conn, $hotel_id) {
    $data = json_decode(file_get_contents("php://input"), true);
    $subscription_id = $data['subscription_id'] ?? '';

    if (!$subscription_id) {
        file_put_contents('debug.log', "Missing subscription_id\n", FILE_APPEND);
        return ['status' => 'error', 'message' => 'Missing subscription_id'];
    }

    $client_id = "AZeXCwrHJs_jNSTFWNl7j1iXXISNpVKmYRIqp6K0vpzQLWb6xTTJsBhnPH2XN3zFoWl3_ge7x-_o_Qam";
    $secret = "EJPJUvMlMYyMNTzVKzC7_mXR6y_FXNQBxHyEoB8uXds4Y2AY9BG1ZRLtlkIuoKgiziJJzIGLxuKJnZWW";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$client_id:$secret");
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
    $response = curl_exec($ch);
    curl_close($ch);

    $token_data = json_decode($response, true);
    $access_token = $token_data['access_token'] ?? null;

    if (!$access_token) {
        file_put_contents('debug.log', "Unable to fetch token\n", FILE_APPEND);
        return ['status' => 'error', 'message' => 'Unable to fetch token'];
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/billing/subscriptions/$subscription_id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $access_token"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $subscription = json_decode($response, true);
    $plan_id = $subscription['plan_id'] ?? '';
    $status = $subscription['status'] ?? 'ACTIVE';
    $start_date = $subscription['start_time'] ?? date('Y-m-d H:i:s');
    $next_billing_date = $subscription['billing_info']['next_billing_time'] ?? null;

    $plan_name = $plan_id === 'P-8C5135325M472893JM7YPBJY' ? 'Pro' : ($plan_id === 'P-17986976C9730405RNAI6REI' ? 'Enterprise' : 'Unknown');
    $amount = $plan_name === 'Pro' ? 29.00 : ($plan_name === 'Enterprise' ? 39.00 : 0.00);
    $currency = 'EUR';

    $query = "
        INSERT INTO subscriptions (
            subscription_id, hotel_id, plan_id, plan_name, status, amount, currency,
            start_date, next_billing_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        'sisssdsss',
        $subscription_id,
        $hotel_id,
        $plan_id,
        $plan_name,
        $status,
        $amount,
        $currency,
        $start_date,
        $next_billing_date
    );
    if (!$stmt->execute()) {
        $error = "Subscription insert error: " . $stmt->error . "\n";
        file_put_contents('debug.log', $error, FILE_APPEND);
        return ['status' => 'error', 'message' => 'Failed to save subscription'];
    }
    $stmt->close();

    $paypal_transaction_id = $subscription_id . '-INIT';
    $transaction_status = 'COMPLETED';
    $transaction_type = 'PAYMENT';
    $transaction_date = $start_date;

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
        $transaction_status,
        $transaction_type,
        $transaction_date
    );
    if (!$stmt->execute()) {
        $error = "Transaction insert error: " . $stmt->error . "\n";
        file_put_contents('debug.log', $error, FILE_APPEND);
        return ['status' => 'error', 'message' => 'Failed to save transaction'];
    }
    $stmt->close();

    file_put_contents('debug.log', "Success: Subscription $subscription_id saved for hotel $hotel_id\n", FILE_APPEND);
    return ['status' => 'success', 'message' => 'Subscription and transaction saved'];
}

$result = verify_subscription($conn, $hotel_id);
echo json_encode($result);
?>