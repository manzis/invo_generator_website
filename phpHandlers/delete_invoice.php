<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// deleteBook.php

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $invoiceId = $_POST["invoice_id"];

    // Perform deletion
    $sql = "DELETE FROM invoices WHERE invoice_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $invoiceId);

    if ($stmt->execute()) {
        $response = array("status" => "success", "message" => "Invoices deleted successfully");
    } else {
        $response = array("status" => "error", "message" => "Error deleting Invoice: " . $stmt->error);
    }

    // Output the response as JSON
    echo json_encode($response);

    $stmt->close();
}

$conn->close();
?>
