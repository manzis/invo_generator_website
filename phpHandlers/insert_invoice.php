<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Retrieve invoice data from POST request
    $user_id = $_POST['user_id']; // This will reference userdata(user_id)
    $invoice_from = $_POST['invoice_from'];
    $invoice_to = $_POST['invoice_to'];
    $ship_to = $_POST['ship_to'];
    $invoice_number = $_POST['invoice_number'];
    $inv_date = $_POST['inv_date'];
    $inv_due_date = $_POST['inv_due_date'];
    $payment_terms = $_POST['payment_terms'];
    $inv_contact = $_POST['inv_contact'];
    $currency = $_POST['currency'];
    $subtotal = $_POST['subtotal'];
    $total_amt = $_POST['total_amt'];
    $amt_paid = $_POST['amt_paid'];
    $balance_due = $_POST['balance_due'];
    $inv_notes = $_POST['inv_notes'];
    $inv_terms = $_POST['inv_terms'];

    // SQL query to insert invoice data
    $sql = "INSERT INTO invoices (user_id, invoice_from, invoice_to, ship_to, invoice_number, inv_date, inv_due_date, payment_terms, contact_number, currency, subtotal, total_amt, amt_paid, balance_due, inv_notes, inv_terms)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isssssssssssssss", $user_id, $invoice_from, $invoice_to, $ship_to, $invoice_number, $inv_date, $inv_due_date, $payment_terms, $inv_contact, $currency, $subtotal, $total_amt, $amt_paid, $balance_due, $inv_notes, $inv_terms);

        // Execute the query
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Invoice inserted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert invoice']);
        }

        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: unable to prepare statement']);
    }

    // Close the database connection
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
