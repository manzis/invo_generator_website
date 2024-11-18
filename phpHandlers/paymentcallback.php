<?php
session_start();

$userId = $_SESSION['user']['user_id'];

$pidx = isset($_GET['pidx']) ? $_GET['pidx'] : null;
$status = isset($_GET['status']) ? $_GET['status'] : null;
$transaction_id = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : null;
$amount = isset($_GET['amount']) ? $_GET['amount'] : null;
$purchase_order_id = isset($_GET['purchase_order_id']) ? $_GET['purchase_order_id'] : null;
$purchase_order_name = isset($_GET['purchase_order_name']) ? $_GET['purchase_order_name'] : null;

// Check for required parameters
if (!$pidx || !$status || !$purchase_order_id || !$purchase_order_name) {
    echo 'Invalid callback parameters';
    exit;
}

// Khalti API endpoint for payment lookup
$khaltiApiUrl = 'https://a.khalti.com/api/v2/epayment/lookup/';
$khaltiSecretKey = 'ecff1e7fcf36497f99ff6796d61f3750';  // Use live_secret_key for production

$payload = [
    'pidx' => $pidx
];

$ch = curl_init($khaltiApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Key ' . $khaltiSecretKey,
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    $responseData = json_decode($response, true);
    $paymentStatus = $responseData['status'];
    $totalAmount = $responseData['total_amount'];

    if ($paymentStatus === 'Completed') {
        // Payment successful
        // Redirect to success page with parameters
        header('Location: https://localhost/projectmis/samples/phpHandlers/sucess.php?purchase_order_id=' . urlencode($purchase_order_id) . '&purchase_order_name=' . urlencode($purchase_order_name) . '&amount=' . urlencode($amount) . '&user_id=' . urlencode($userId));
        exit;
    } else {
        // Payment failed or pending
        header('Location: https://example.com/failure.php');
        exit;
    }
} else {
    echo 'Error occurred during payment verification';
}
?>
