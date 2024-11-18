<?php
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "invobook";
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from POST request
$userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

// Validate user ID
if ($userId <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid user ID']);
    exit;
}

// Retrieve the user's current plan details
$sql = "SELECT UP.plan_id, P.plan_name, P.invoice_limit, P.expiry_date 
        FROM user_plans UP 
        JOIN plans P ON UP.plan_id = P.plan_id 
        WHERE UP.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);

if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $plan = $result->fetch_assoc();

        // Check if the plan has expired
        $currentDate = new DateTime();
        $expiryDate = new DateTime($plan['expiry_date']);

        if ($expiryDate < $currentDate) {
            // Plan expired
            echo json_encode(['status' => 'redirect', 'message' => 'Plan expired']);
            exit;
        }

        // Check invoice limit for free plans
        if ($plan['plan_name'] === 'Free') {
            // Check the invoice limit
            if ($plan['invoice_limit'] <= 0) {
                // No credits left
                echo json_encode(['status' => 'redirect', 'message' => 'No credits left']);
                exit;
            } else {
                // User has credits left
                echo json_encode(['status' => 'success']);
                exit;
            }
        } else {
            // Paid plan
            echo json_encode(['status' => 'success']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'redirect', 'message' => 'No plan found']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    exit;
}
?>
