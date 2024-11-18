<?php
session_start();
include('db_connect.php');

$user_id = $_SESSION['user']['user_id'];

// Increment the number of invoices created for the user
$query = "UPDATE User_Plan SET invoices_created = invoices_created + 1 WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo 'Invoice saved successfully.';
} else {
    echo 'Error saving invoice.';
}

$conn->close();
?>
