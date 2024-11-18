<?php
session_start();

$host = "localhost";
$username = "root";
$password = "";
$database = "invobook";

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user']['user_id'];

// Get the user's plan and invoice count
$query = "SELECT plan_id, invoice_count FROM user_data WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $plan_id = $user['plan_id'];
    $invoice_count = $user['invoice_count'];

    // Fetch plan details if the user has a plan
    if ($plan_id) {
        $plan_query = "SELECT limit FROM Plans WHERE plan_id = ?";
        $plan_stmt = $conn->prepare($plan_query);
        $plan_stmt->bind_param("i", $plan_id);
        $plan_stmt->execute();
        $plan_result = $plan_stmt->get_result();
        $plan = $plan_result->fetch_assoc();

        if ($plan && $plan['limit'] <= $invoice_count) {
            // Plan limit reached
            echo "Upgrade your plan to create more invoices.";
        } else {
            // Allow invoice creation
            // Increment invoice count for free users
            if (!$plan_id) {
                $update_query = "UPDATE user_data SET invoice_count = invoice_count + 1 WHERE user_id = ?";
                $update_stmt = $conn->prepare($update_query);
                $update_stmt->bind_param("i", $user_id);
                $update_stmt->execute();
            }
            echo "Invoice created successfully.";
        }
    } else {
        // Free user limit check
        if ($invoice_count >= 3) {
            echo "Upgrade your plan to create more invoices.";
        } else {
            // Increment invoice count
            $update_query = "UPDATE user_data SET invoice_count = invoice_count + 1 WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("i", $user_id);
            $update_stmt->execute();
            echo "Invoice created successfully.";
        }
    }
} else {
    echo "User not found.";
}

$conn->close();
?>
