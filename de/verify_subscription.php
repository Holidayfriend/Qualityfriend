<?php
require_once 'util_config.php';
require_once '../util_session.php';

// Suppress errors in output to prevent JSON corruption
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Adjust for production
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

function verify_subscription($conn, $hotel_id) {
    // Log raw input
    $raw_input = file_get_contents("php://input");
    file_put_contents('debug.log', "Raw input: $raw_input\n", FILE_APPEND);
    
    // Parse JSON input
    $data = json_decode($raw_input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $error = "JSON decode error: " . json_last_error_msg() . "\n";
        file_put_contents('debug.log', $error, FILE_APPEND);
        return ['status' => 'error', 'message' => 'Invalid JSON input'];
    }

    $subscription_id = $data['subscription_id'] ?? '';
    if (!$subscription_id) {
        file_put_contents('debug.log', "Missing subscription_id\n", FILE_APPEND);
        return ['status' => 'error', 'message' => 'Missing subscription_id'];
    }

    $client_id = "ARLwsTJTnKPjBrJqYGW_ji0SiP4YnlREf6xdkSfVl4mKug3lCYm3qWlDOysG4-FGSCH7jeF2VyED4y8z";
    $secret = "EJPJUvMlMYyMNTzVKzC7_mXR6y_FXNQBxHyEoB8uXds4Y2AY9BG1ZRLtlkIuoKgiziJJzIGLxuKJnZWW";

    // Get access token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$client_id:$secret");
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    // Log cURL response
    file_put_contents('debug.log', "Token cURL response: $response, HTTP: $http_code, Error: $curl_error\n", FILE_APPEND);
    
    if ($response === false || $http_code !== 200) {
        file_put_contents('debug.log', "Token cURL failed: $curl_error, HTTP: $http_code\n", FILE_APPEND);
        return ['status' => 'error', 'message' => 'Unable to fetch token'];
    }

    $token_data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $error = "Token JSON decode error: " . json_last_error_msg() . "\n";
        file_put_contents('debug.log', $error, FILE_APPEND);
        return ['status' => 'error', 'message' => 'Invalid token response'];
    }

    $access_token = $token_data['access_token'] ?? null;
    if (!$access_token) {
        file_put_contents('debug.log', "No access token in response\n", FILE_APPEND);
        return ['status' => 'error', 'message' => 'Unable to fetch token'];
    }

    // Get subscription details
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api-m.sandbox.paypal.com/v1/billing/subscriptions/$subscription_id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $access_token"
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    // Log cURL response
    file_put_contents('debug.log', "Subscription cURL response: $response, HTTP: $http_code, Error: $curl_error\n", FILE_APPEND);
    
    if ($response === false || $http_code !== 200) {
        file_put_contents('debug.log', "Subscription cURL failed: $curl_error, HTTP: $http_code\n", FILE_APPEND);
        return ['status' => 'error', 'message' => 'Unable to fetch subscription'];
    }

    $subscription = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $error = "Subscription JSON decode error: " . json_last_error_msg() . "\n";
        file_put_contents('debug.log', $error, FILE_APPEND);
        return ['status' => 'error', 'message' => 'Invalid subscription response'];
    }

    $plan_id = $subscription['plan_id'] ?? '';
    $status = $subscription['status'] ?? 'ACTIVE';
    $start_date = $subscription['start_time'] ?? date('Y-m-d H:i:s');
    $next_billing_date = $subscription['billing_info']['next_billing_time'] ?? null;

    $plan_name = $plan_id === 'P-8C5135325M472893JM7YPBJY' ? 'Pro' : ($plan_id === 'P-9VE77323MD893801UM7YBV2Q' ? 'Enterprise' : 'Unknown');
    $amount = $plan_name === 'Pro' ? 29.00 : ($plan_name === 'Enterprise' ? 39.00 : 0.00);
    $currency = 'EUR';

    // Insert subscription
    $query = "
        INSERT INTO subscriptions (
            subscription_id, hotel_id, plan_id, plan_name, status, amount, currency,
            start_date, next_billing_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        $error = "Subscription prepare error: " . $conn->error . "\n";
        file_put_contents('debug.log', $error, FILE_APPEND);
        return ['status' => 'error', 'message' => 'Database prepare failed'];
    }
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
        $stmt->close();
        return ['status' => 'error', 'message' => 'Failed to save subscription'];
    }
    $stmt->close();

    // Insert transaction
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
    if (!$stmt) {
        $error = "Transaction prepare error: " . $conn->error . "\n";
        file_put_contents('debug.log', $error, FILE_APPEND);
        return ['status' => 'error', 'message' => 'Database prepare failed'];
    }
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
        $stmt->close();
        return ['status' => 'error', 'message' => 'Failed to save transaction'];
    }
    $stmt->close();

    file_put_contents('debug.log', "Success: Subscription $subscription_id saved for hotel $hotel_id\n", FILE_APPEND);
    return ['status' => 'success', 'message' => 'Subscription and transaction saved'];
}

// Ensure a JSON response is always output
try {
    $result = verify_subscription($conn, $hotel_id);
} catch (Exception $e) {
    $error = "Unexpected error: " . $e->getMessage() . "\n";
    file_put_contents('debug.log', $error, FILE_APPEND);
    $result = ['status' => 'error', 'message' => 'Unexpected server error'];
}
echo json_encode($result);
?>