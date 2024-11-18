<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Database connection using mysqli
$host = "localhost";
$username = "root";
$password = "";
$database = "invobook";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Database connection successful<br>";

// Retrieve parameters from the callback
$purchaseOrderId = isset($_GET['purchase_order_id']) ? $_GET['purchase_order_id'] : null;
$purchaseOrderName = isset($_GET['purchase_order_name']) ? $_GET['purchase_order_name'] : null;
$amount = isset($_GET['amount']) ? $_GET['amount'] : null;
$userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;

// Check for required parameters
if (!$purchaseOrderId || !$purchaseOrderName || !$amount || !$userId) {
    die('Invalid parameters<br>');
}

// Determine the plan details
$planDetails = [
    'Starter' => ['invoice_limit' => -1, 'price' => 29900, 'duration' => 30],  // -1 indicates unlimited
    'Premium' => ['invoice_limit' => -1, 'price' => 59900, 'duration' => 30],
    'Saver' => ['invoice_limit' => -1, 'price' => 99900, 'duration' => 365]  // -1 indicates unlimited
];

// Ensure the plan exists
if (!isset($planDetails[$purchaseOrderName])) {
    die('Invalid plan name<br>');
}

// Get the plan details
$plan = $planDetails[$purchaseOrderName];
$invoiceLimit = $plan['invoice_limit'];
$price = $plan['price'];
$duration = $plan['duration'];
$purchaseDate = date('Y-m-d');
$expiryDate = date('Y-m-d', strtotime("+$duration days", strtotime($purchaseDate)));

echo "Purchase Date: $purchaseDate<br>";
echo "Expiry Date: $expiryDate<br>";

// Validate date format
if (!DateTime::createFromFormat('Y-m-d', $purchaseDate) || !DateTime::createFromFormat('Y-m-d', $expiryDate)) {
    die("Invalid date format.<br>");
}

// Check if the user already has a plan
$sql = "SELECT P.plan_id, P.plan_name 
        FROM user_plans UP 
        JOIN plans P ON UP.plan_id = P.plan_id 
        WHERE UP.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);

if (!$stmt) {
    die("Prepare failed: " . htmlspecialchars($conn->error) . "<br>");
}

if (!$stmt->execute()) {
    die("Execute failed: " . htmlspecialchars($stmt->error) . "<br>");
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Existing plan found, update the plan details if necessary
    $existingPlan = $result->fetch_assoc();

    if ($existingPlan['plan_name'] === $purchaseOrderName) {
        // Plan names are the same, only update the expiry date
        $sql = "UPDATE plans 
                SET expiry_date = ? 
                WHERE plan_id = ?";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . htmlspecialchars($conn->error) . "<br>");
        }

        $stmt->bind_param('si', $expiryDate, $existingPlan['plan_id']);

        if (!$stmt->execute()) {
            die("Execute failed: " . htmlspecialchars($stmt->error) . "<br>");
        }

        echo 'Plan expiry date updated successfully.<br>';
        header("Location: ../package/package.php");
        exit();
        
    } else {
        // Plan names are different, update all details except plan ID
        $sql = "UPDATE plans 
                SET plan_name = ?, price = ?, invoice_limit = ?, purchase_date = ?, duration = ?, expiry_date = ? 
                WHERE plan_id = ?";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . htmlspecialchars($conn->error) . "<br>");
        }

        $stmt->bind_param('siiissi', $purchaseOrderName, $price, $invoiceLimit, $purchaseDate, $duration, $expiryDate, $existingPlan['plan_id']);

        if (!$stmt->execute()) {
            die("Execute failed: " . htmlspecialchars($stmt->error) . "<br>");
        }

        echo 'Plan details updated successfully.<br>';
        header("Location: ../package/package.php");
        exit();
    }
} else {
    // No existing plan, create a new plan record
    $sql = "INSERT INTO plans (plan_name, price, invoice_limit, purchase_date, duration, expiry_date) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . htmlspecialchars($conn->error) . "<br>");
    }

    $stmt->bind_param('siiiss', $purchaseOrderName, $price, $invoiceLimit, $purchaseDate, $duration, $expiryDate);

    if (!$stmt->execute()) {
        die("Execute failed: " . htmlspecialchars($stmt->error) . "<br>");
    }

    // Get the newly created plan ID
    $planId = $stmt->insert_id;

    // Associate the new plan with the user
    $sql = "INSERT INTO user_plans (user_id, plan_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . htmlspecialchars($conn->error) . "<br>");
    }

    $stmt->bind_param('ii', $userId, $planId);

    if (!$stmt->execute()) {
        die("Execute failed: " . htmlspecialchars($stmt->error) . "<br>");
    }

    echo 'New plan created and associated with user.<br>';
    header("Location: ../package/package.php");
}

?>
