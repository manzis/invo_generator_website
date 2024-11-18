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
function sanitizeInput($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}

// Validate and check login data only if the action is 'login'
if ($_POST['action'] === 'login') {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password']; // Don't hash it here, we'll compare it later

    // Server-side validation (you can add more validation if needed)
    if (empty($email) || empty($password)) {
        echo "Please fill in all fields";
    } else {
        // Check if the email exists in the database
        $checkEmailQuery = "SELECT * FROM user_data WHERE email = '$email'";
        $result = $conn->query($checkEmailQuery);

        if ($result->num_rows > 0) {
            // Email exists, check if the password matches
            $user = $result->fetch_assoc();
            $id=$user["user_id"];
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = array(
                    'email' => $user['email'],
                    'fullName' => $user['username'],
                    'user_id'=> $user['user_id'],
                    'org_name' => $user['organization_name'],
                   
                );


                    echo "successful";
                
            } else {
                echo "Password doesn't matched";
            }
        } else {
            echo "Email adddress or password is incorrect";
        }
    }
}


$conn->close();
?>