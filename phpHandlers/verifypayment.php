    <?php
    session_start();

        $email = $_SESSION['user']['email'];
        $username = $_SESSION['user']['fullName']; 
        $userId = $_SESSION['user']['user_id'];


    header('Content-Type: application/json');

    // Read and decode the JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Check if JSON decoding was successful
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['error' => 'Invalid JSON']);
        exit;
    }

    // Validate the required fields
    $package = isset($data['package']) ? $data['package'] : null;
    $amount = isset($data['amount']) ? $data['amount'] : null;

    if ($package === null || $amount === null) {
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Sanitize and validate package and amount (example validation)
    $validPackages = ['Starter', 'Premium', 'Saver'];
    if (!in_array($package, $validPackages)) {
        echo json_encode(['error' => 'Invalid package']);
        exit;
    }

    if (!is_numeric($amount) || $amount <= 0) {
        echo json_encode(['error' => 'Invalid amount']);
        exit;
    }

    // Khalti API endpoint and credentials
    $khaltiApiUrl = 'https://a.khalti.com/api/v2/epayment/initiate/';
    $khaltiSecretKey = 'ecff1e7fcf36497f99ff6796d61f3750';  // Use live_secret_key for production

    // Prepare data for Khalti API
    $payload = [
        "return_url" => "https://localhost/projectmis/samples/phpHandlers/paymentcallback.php",
        "website_url" => "https://example.com/",
        "amount" => $amount,  // Amount in paisa
        "purchase_order_id" => uniqid('order_'),  // Unique ID for the transaction
        "purchase_order_name" => $package,
        "customer_info" => [
            "name" => $username ,
            "email" => $email,
        ]
    ];

    // Initialize cURL
    $ch = curl_init($khaltiApiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Key ' . $khaltiSecretKey,
        'Content-Type: application/json'
    ]);

    // Execute request and handle response
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        $responseData = json_decode($response, true);
        echo json_encode($responseData);
    } else {
        echo json_encode(['error' => 'Failed to initiate payment', 'details' => $response]);
    }
    ?>


