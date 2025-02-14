<?php
// Set response header
header('Content-Type: text/html; charset=UTF-8');

session_start();

// Database connection details
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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Sanitize and validate input values
    $pidx = htmlspecialchars(filter_input(INPUT_GET, 'pidx', FILTER_SANITIZE_STRING) ?? 'N/A');
    $status = htmlspecialchars(filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING) ?? 'Unknown');
    $transaction_id = htmlspecialchars(filter_input(INPUT_GET, 'transaction_id', FILTER_SANITIZE_STRING) ?? 'N/A');
    $tidx = htmlspecialchars(filter_input(INPUT_GET, 'tidx', FILTER_SANITIZE_STRING) ?? 'N/A');
    $amount = filter_input(INPUT_GET, 'amount', FILTER_VALIDATE_INT);
    $khalti_id = htmlspecialchars(filter_input(INPUT_GET, 'mobile', FILTER_SANITIZE_STRING) ?? 'N/A');
    $purchase_order_id = htmlspecialchars(filter_input(INPUT_GET, 'purchase_order_id', FILTER_SANITIZE_STRING) ?? null);
    $purchase_order_name = htmlspecialchars(filter_input(INPUT_GET, 'purchase_order_name', FILTER_SANITIZE_STRING) ?? 'N/A');
    $total_amount = filter_input(INPUT_GET, 'total_amount', FILTER_VALIDATE_INT);

    // Retrieve user_id from session
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        die("<h3 style='text-align:center;color:red;'>Error: User is not logged in. Please log in to continue.</h3>");
    }

    // Debugging user_id
    if (empty($user_id)) {
        die("<h3 style='text-align:center;color:red;'>Error: User ID is not set or is null.</h3>");
    }

    // Debugging purchase_order_id (pet_id)
    if (empty($purchase_order_id)) {
        die("<h3 style='text-align:center;color:red;'>Error: purchase_order_id (pet_id) is null or not provided.</h3>");
    }

    // Debugging amount (payment_value)
    if (empty($amount)) {
        die("<h3 style='text-align:center;color:red;'>Error: Payment value (amount) is null or not provided.</h3>");
    }

    // Save payment details in the `payments` table
    $stmt = $conn->prepare("INSERT INTO payments (user_id, pet_id, payment_value, payment_status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iids", $user_id, $purchase_order_id, $amount, $status);

    if ($stmt->execute()) {
        echo "<h3 style='text-align:center;color:green;'>Payment details saved successfully.</h3>";
    } else {
        echo "<h3 style='text-align:center;color:red;'>Error saving payment: " . $stmt->error . "</h3>";
    }
    $stmt->close();

    // Define background color based on status
    $background_color = match ($status) {
        'Completed' => 'background-color:rgb(8, 211, 15);',
        'Pending', 'Initiated', 'Refunded', 'Partially Refunded' => 'background-color:rgb(239, 175, 85);',
        'Expired', 'User canceled' => 'background-color: #f44336;',
        default => 'background-color:rgb(253, 246, 185);',
    };

    // Display the payment information
    echo "
    <html>
    <head>
        <title>Payment Information</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f9f9f9;
                margin: 0;
                padding: 20px;
            }
            table {
                width: 80%;
                border-collapse: collapse;
                margin: 20px auto;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                background-color: #fff;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 12px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
                font-weight: bold;
            }
            h2 {
                text-align: center;
                color: #333;
            }
            button {
                background-color: #4CAF50;
                border: none;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: block;
                margin: 20px auto;
                font-size: 16px;
                cursor: pointer;
                border-radius: 5px;
            }
            button:hover {
                background-color: #45a049;
            }
        </style>
    </head>
    <body>
        <h2>Payment Information</h2>
        <table>
            <tr><th>Field</th><th>Details</th></tr>
            <tr><td>Product ID</td><td>$pidx</td></tr>
            <tr><td>Status</td><td style=\"$background_color\">$status</td></tr>
            <tr><td>Transaction ID</td><td>$transaction_id</td></tr>
            <tr><td>Transaction Index</td><td>$tidx</td></tr>
            <tr><td>Amount</td><td>Rs. " . number_format($amount / 100, 2) . "</td></tr>
            <tr><td>Khalti ID</td><td>$khalti_id</td></tr>
            <tr><td>Purchase Order Name</td><td>$purchase_order_name</td></tr>
            <tr><td>Total Amount</td><td>Rs. " . number_format($total_amount / 100, 2) . "</td></tr>
        </table>
        <button onclick=\"window.location.href = 'http://localhost/PetLink_Project-master/User/Dashboard.php';\">Go Back</button>
    </body>
    </html>";
} else {
    echo "<h3 style='text-align:center;color:red;'>Invalid request method. Please use GET.</h3>";
}
?>
