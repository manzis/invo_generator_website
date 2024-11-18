<?php
session_start();

if (isset($_SESSION['user'])) {
    // Access session variables
    $email = $_SESSION['user']['email'];
    $fullName = $_SESSION['user']['fullName']; 
    $userId = $_SESSION['user']['user_id'];
    $orgName =$_SESSION['user']['org_name'];
} else {
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

// Retrieve the user's current plan details
$sql = "SELECT P.plan_name, P.invoice_limit, P.purchase_date, P.duration, P.expiry_date 
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
        $invoiceLimit = $row['invoice_limit'];
        $expiryDate = $row['expiry_date'];

        // Calculate remaining days for paid plans
        $today = new DateTime();
        $expiryDateObj = new DateTime($expiryDate);
        $remainingDays = $today->diff($expiryDateObj)->format('%a');

        // Determine plan info based on plan type
        if ($planName !== 'Free') {
            $planInfo = "$planName Plan: $remainingDays Days left";
        } else {
            $planInfo = "Free Plan: $invoiceLimit credits left";
        }
    } else {
       
        $planInfo = "Free Plan: 0 credits left";
    }
} else {
    echo "Error: " . $stmt->error;
}
?>

<div class="nav-frame-1">
  <div class="nav-frame-2" id="homepageBtn">
    <div class="nav-button"><span class="nav-invo">Invo</span></div><span class="nav-generator">Generator</span>
  </div>
  <div class="nav-frame-3">
    <div class="nav-frame-4">
      <div class="nav-frame-5">
        <button class="nav-my-invoices" id="myinvoiceBtn">My Invoices</button>
        <button class="nav-settings" id="createBtn">Create</button>
        <button class="nav-help" id="helpBtn">Help</button>
        <button class="nav-frame-6" id="upgradeBtn">
          <div class="nav-icons-sparkle"></div>
          <span class="nav-upgrade">Upgrade</span>
        </button>
      </div>
    </div>
    <div class="profile-div">
      <div class="planinfo"><?php echo htmlspecialchars($planInfo); ?></div>
      <button class="nav-frame-7" id="dropDownBtn" onclick="toggleDropdown1();">
        <span class="nav-man-jish"><?php echo htmlspecialchars($fullName); ?> </span>
        <div class="nav-arrow-down">
          <div class="nav-vector"></div>
        </div>
      </button>
      <div class="dropdown-menu-1" id="dropdownMenu1">
        <a href="#" class="myprofile" id="mypfl_btn">My Profile</a>
        <a href="#" class="a-invoice " id="navinvoiceBtn">Invoices</a>
        <a href="#" class="a-settings">Settings</a>
        <a href="../phpHandlers/logout.php" class="logout">Log Out</a>
      </div>
    </div>
  </div>
</div>
