<?php

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

// Function to sanitize input data
function sanitizeInput($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

// Function to hash the password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Validate and insert data only if the action is 'signup'
if ($_POST['action'] === 'signup') {
    $fullName = sanitizeInput($_POST['fullName']);
    $email = sanitizeInput($_POST['email']);
    $organization_name = sanitizeInput($_POST['orgname']);
    $password = hashPassword($_POST['password']);

    // Server-side validation
    if (empty($fullName) || empty($email) || empty($_POST['password'])) {
        echo "Please fill in all fields";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
    } elseif (strlen($_POST['password']) < 8) {
        echo "Password must be at least 8 characters long";
    } else {
        // Check if the email is already registered
        $checkEmailQuery = $conn->prepare("SELECT * FROM user_data WHERE email = ?");
        $checkEmailQuery->bind_param("s", $email);
        $checkEmailQuery->execute();
        $result = $checkEmailQuery->get_result();

        if ($result->num_rows > 0) {
            echo "Provided email has already been used";
        } else {
            // Insert user data into user_data table
            $insertUserQuery = $conn->prepare("INSERT INTO user_data (username, email, password, organization_name) VALUES (?, ?, ?, ?)");
            $insertUserQuery->bind_param("ssss", $fullName, $email, $password, $organization_name);

            if ($insertUserQuery->execute()) {
                // Retrieve the last inserted user_id
                $user_id = $conn->insert_id;

                // Insert the free plan for the user
                $freePlanName = "Free";
                $invoiceLimit = 5;

                // Insert into plans table (if the plan doesn't exist, create it)
                $insertPlanQuery = $conn->prepare("INSERT INTO plans (plan_name, invoice_limit) VALUES (?, ?) ON DUPLICATE KEY UPDATE plan_id=LAST_INSERT_ID(plan_id)");
                $insertPlanQuery->bind_param("si", $freePlanName, $invoiceLimit);
                $insertPlanQuery->execute();

                // Get the plan_id of the inserted/retrieved plan
                $plan_id = $conn->insert_id;

                // Insert into user_plans table
                $insertUserPlanQuery = $conn->prepare("INSERT INTO user_plans (user_id, plan_id) VALUES (?, ?)");
                $insertUserPlanQuery->bind_param("ii", $user_id, $plan_id);
                $insertUserPlanQuery->execute();

                echo "Sign up successful, free plan assigned. Redirecting to Login Page.";
            } else {
                echo "Error: " . $insertUserQuery->error;
            }

            // Close the prepared statements
            $insertUserQuery->close();
            $insertPlanQuery->close();
            $insertUserPlanQuery->close();
        }

        // Close the prepared statement
        $checkEmailQuery->close();
    }
}

// Close the connection
$conn->close();
?>
