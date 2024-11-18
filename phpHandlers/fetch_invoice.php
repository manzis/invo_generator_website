<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "invobook"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch invoice data
$sql = "SELECT invoice_number, invoice_id, invoice_to, inv_date, total_amt, contact_number, status FROM invoices WHERE user_id = $userId ORDER BY invoice_id DESC";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Fetch each row and output HTML
    while ($row = $result->fetch_assoc()):
        $inv_number = htmlspecialchars($row["invoice_number"]);
        $inv_to = htmlspecialchars($row["invoice_to"]);
        $inv_date = htmlspecialchars($row["inv_date"]);
        $total_amt = htmlspecialchars($row["total_amt"]);
        $contact_number = htmlspecialchars($row["contact_number"]);
        $status = $row["status"] == 'paid' ? 'Paid' : 'Unpaid';
        $status_img = $row["status"] == 'paid' ? 'paid.png' : 'unpaid.png';
        $invoice_id = htmlspecialchars($row["invoice_id"]);

        // Determine label for the "Mark as Paid/Unpaid" link
        $markAsLabel = ($row["status"] == 'paid') ? 'Mark as Unpaid' : 'Mark as Paid';
        ?>
        <div class="frame-1e">
            <div class="frame-1f">
                <div class="frame-20">
                    <div class="frame-21">
                        <span class="manjish-upadhaya"><?php echo $inv_to; ?></span>
                    </div>
                    <div class="frame-22">
                        <span class="invoice">Invoice #<?php echo $inv_number; ?></span>
                    </div>
                    <div class="frame-23">
                        <span class="aug"><?php echo $inv_date; ?></span>
                    </div>
                    <div class="frame-24">
                        <span class="phone"><?php echo $contact_number; ?></span>
                    </div>
                    <div class="frame-25">
                        <span class="paid"><?php echo $status; ?></span>
                        <img src="assets/images/<?php echo $status_img; ?>" class="paidimg">
                    </div>
                    <div class="frame-26">
                        <span class="rs">Rs.</span><span class="amount"><?php echo $total_amt; ?></span>
                    </div>
                </div>
                <div class="frame-27">
                    <button class="frame-28">
                        <div class="frame-29"><span class="view">View</span></div>
                        <div class="frame-2a" onclick="toggleDropdown(<?php echo $invoice_id;?>)">
                            <div class="arrow-down-2b">
                                <div class="vector-2c"></div>
                            </div>
                        </div>
                    </button>
                    <div class="dropdown-menu" id="dropdownMenu_<?php echo $invoice_id; ?>">
                        <a href="#" onclick="editInvoice(<?php echo $invoice_id; ?>)">Edit</a>
                        <a href="#" onclick="downloadInvoice(<?php echo $invoice_id; ?>)">Download</a>
                        <!-- Conditionally set the label based on current status -->
                        <a href="#" onclick="markAsPaid(<?php echo $invoice_id; ?>)"><?php echo $markAsLabel; ?></a>
                        <a href="#" onclick="deleteInvoice(<?php echo $invoice_id; ?>)">Delete</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    endwhile;
} else {
    echo 'No invoices found.';
}

$conn->close();
?>
