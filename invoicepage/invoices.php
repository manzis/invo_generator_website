<?php
session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['user_id'])) {
  // User is not logged in, redirect to the login page
  header("Location: ../loginpage/loginsignup.php");
  exit;

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Helvetica:wght@400;700&display=swap" />
  <link rel="stylesheet" href="index.css" />
  <link rel="stylesheet" href="../home/nav.css" />
</head>

<body>
<?php include "../home/nav.php" ?>
  <div class="main-container" id="divlock">
   
    <div class="frame-a">
      <div class="frame-b">
        <div class="frame-c">
          <div class="frame-d">
            <span class="my-invoices-e">My Invoices</span>
          </div>
          <div class="frame-f">
            <button class="frame-10" id="newinvoiceBtn">
              <span class="new-invoice">New Invoice</span>
            </button>
            <div class="frame-11">
              <div class="icons-down-arrow"></div>
            </div>
          </div>
        </div>
        <div class="frame-12">
          <div class="frame-13">
            <div class="frame-14">
              <div class="line"></div>
            </div>
            <div class="frame-15">
              <div class="frame-16">
                <span class="customer">CUSTOMER</span>
              </div>
              <div class="frame-17">
                <span class="reference">REFERENCE</span>
              </div>
              <div class="frame-18"><span class="date">DATE</span></div>
              <div class="frame-19">
                <span class="contact-info">CONTACT INFO</span>
              </div>
              <div class="frame-1a"><span class="status">STATUS</span></div>
              <div class="frame-1b"><span class="total">TOTAL</span></div>
            </div>
            <div class="frame-1c">
              <div class="line-1d"></div>
            </div>
          </div>
        <?php include "../phpHandlers/fetch_invoice.php" ?>
        </div>
      </div>
    </div>
    <script src="../myjs/eventhandling.js"></script>
</body>

</html>