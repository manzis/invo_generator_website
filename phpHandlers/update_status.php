<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "invobook";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the invoice ID from the AJAX request
    $invoice_id = $_POST['invoice_id'];

    // Step 1: Retrieve the current status of the invoice
    $query = "SELECT status FROM invoices WHERE invoice_id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $invoice_id);
        $stmt->execute();
        $stmt->bind_result($current_status);
        $stmt->fetch();
        $stmt->close();

        // Step 2: Toggle the status
        if ($current_status == 'paid') {
            $new_status = 'unpaid';
        } else {
            $new_status = 'paid';
        }

        // Step 3: Update the invoice status with the new value
        $update_query = "UPDATE invoices SET status = ? WHERE invoice_id = ?";
        
        if ($update_stmt = $conn->prepare($update_query)) {
            $update_stmt->bind_param("si", $new_status, $invoice_id);
            $update_stmt->execute();

            echo json_encode(['status' => 'success', 'message' => 'Payment Status updated successfully']);
            $update_stmt->close();
            
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to prepare the update query.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare the select query.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

// Close the database connection
$conn->close();
?>
