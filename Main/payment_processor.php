<?php
session_start(); // Start the session


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$pet_id = isset($_GET['pet_id']) ? $_GET['pet_id'] : null;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pet_link_project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

require "../Configuration/config.php"; // Include database connection and secret key

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit();
}

function initiate_payment($response_data) {
    if (isset($response_data['payment_url'])) {
        $payment_url = $response_data['payment_url'];
        header('Location: ' . $payment_url);
        exit();
    } else {
        echo "Payment URL not found in the response.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_btn'])) {
    // Sanitize and retrieve form data
    $full_name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $phone = htmlspecialchars($_POST['contact'] ?? '');
    $total_price = (int) ($_POST['number'] ?? 0);
    $pet_id = htmlspecialchars($_POST['pet_id'] ?? '');
    $pet_name = htmlspecialchars($_POST['pet_name'] ?? '');
    $user_id = $_SESSION['user_id']; // Assuming user is logged in and session exists

    // Check if the database connection is successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Save the buy form data with a status of 'Pending' (for example)
    $stmt = $conn->prepare("INSERT INTO buy_form_data (user_id, pet_id, name, email, contact, pet_name, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("iissss", $user_id, $pet_id, $full_name, $email, $phone, $pet_name);

    if (!$stmt->execute()) {
        echo "Error saving data: " . $stmt->error;
        exit();
    }
    
    $buy_form_id = $stmt->insert_id; // Get the ID of the inserted row
    $stmt->close();

    // Prepare transaction data for Khalti
    $transaction_data = [
        'return_url' => 'http://localhost/PetLink_Project-master/Main/payment_verification.php?buy_form_id=' . $buy_form_id,  // Pass buy_form_id
        'website_url' => 'http://localhost/PetLink_Project-master',
        'amount' => $total_price * 100, // Convert to paisa
        'purchase_order_id' => $pet_id,
        'purchase_order_name' => $pet_name,
        'customer_info' => [
            'name' => $full_name,
            'email' => $email,
            'phone' => $phone,
        ],
    ];

    // Initiate payment using Khalti API
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://dev.khalti.com/api/v2/epayment/initiate/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($transaction_data),
        CURLOPT_HTTPHEADER => [
            'Authorization: Key ' . $khalti_secret_key,
            'Content-Type: application/json',
        ],
    ]);

    $response = curl_exec($curl);
    $response_data = json_decode($response, true);

    if ($response === false) {
        echo "CURL Error: " . curl_error($curl);
    } elseif (isset($response_data['error_key'])) {
        echo "Khalti Error: " . htmlspecialchars($response_data['error_key']);
    } else {
        initiate_payment($response_data); // Redirect user to Khalti payment page
    }

    curl_close($curl);
}
?>
