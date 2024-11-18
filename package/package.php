<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['user_id'])) {
  // User is not logged in, redirect to the login page
  header("Location: ../loginpage/loginsignup.php");
  exit;

}

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


$userId = $_SESSION['user']['user_id'];

// Retrieve the user's current plan details
$sql = "SELECT P.plan_name, P.duration 
        FROM user_plans UP 
        JOIN plans P ON UP.plan_id = P.plan_id 
        WHERE UP.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);

if ($stmt->execute()) {
  $result = $stmt->get_result();
  
  if ($result->num_rows > 0) {
      // Fetch plan details
      $row = $result->fetch_assoc();
      $planName = $row['plan_name'];
      $planDuration = $row['duration'];
  } else {
      $planName = "Free";
      $planDuration = "3";
  }
} else {
  echo "Error: " . $stmt->error;
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Plan Information</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Helvetica:wght@400;700&display=swap"
    />
    <link rel="stylesheet" href="index.css" />
    <link rel="stylesheet" href="../home/nav.css" />
  </head>
  <body>
  <?php include "../home/nav.php" ?>
    <div class="main-container">
    
      <div class="frame-8">
        <div class="frame-9">
          <div class="frame-a">
            <div class="frame-b">
              <div class="frame-c">
                <button class="button-d">
                  <span class="starter"><?php echo htmlspecialchars($planName); ?></span>
                </button>
                <span class="activated-starter-plan">
                  Successfully Activated the <?php echo htmlspecialchars($planName); ?> Plan. You get unlimited Invoice Creation for the Next <?php echo htmlspecialchars($planDuration); ?> Days!
                </span>
                <button class="button-e" id="letsCreate">
                  <span class="lets-create">Let’s Create</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script src="../myjs/eventHandling.js"></script>
</html>
