<?php
session_start();

// Check if user is logged in
if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['user_id'];

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

    // Check if the user is on the Free Plan and get the invoice limit from the plans table
    $sql = "SELECT P.plan_name, P.invoice_limit 
            FROM user_plans UP 
            JOIN plans P ON UP.plan_id = P.plan_id 
            WHERE UP.user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $planName = $row['plan_name'];
            $invoiceLimit = $row['invoice_limit'];

            // Check if the user is on the Free Plan
            if ($planName === 'Free') {
                // If invoice limit is greater than 0, decrement it
                if ($invoiceLimit > 0) {
                    $newInvoiceLimit = $invoiceLimit - 1;

                    // Update the invoice limit in the plans table (since limit is in the plans table)
                    $updateSql = "UPDATE plans 
                                  SET invoice_limit = ? 
                                  WHERE plan_id = (SELECT plan_id FROM user_plans WHERE user_id = ?)";

                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bind_param('ii', $newInvoiceLimit, $userId);

                    if ($updateStmt->execute()) {
                        echo json_encode(['status' => 'success', 'message' => 'Invoice limit decremented']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to update invoice limit']);
                    }

                    $updateStmt->close();
                } else {
                    // If invoice limit is 0, restrict the user
                    echo json_encode(['status' => 'error', 'message' => 'Invoice limit reached. Please upgrade your plan.']);
                }
            } else {
                // If the user is on a paid plan, no need to decrement the invoice limit
                echo json_encode(['status' => 'success', 'message' => 'Plan user. No need to decrement invoice limit.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User plan not found']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
}
?>
