<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Validate pet_id
if (!isset($_POST['pet_id'])) {
    echo "Pet ID is required.";
    exit();
}

$user_id = $_SESSION['user_id'];
$pet_id = $_POST['pet_id'];

// Database connection parameters
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

// Check if the pet is already in the cart
$sql_check = "SELECT * FROM cart WHERE user_id = ? AND pet_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $user_id, $pet_id);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    echo "Pet is already in your cart.";
} else {
    // Insert into cart if not already in the cart
    $sql_insert = "INSERT INTO cart (user_id, pet_id) VALUES (?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ii", $user_id, $pet_id);

    if ($stmt_insert->execute()) {
        echo "Pet added to cart successfully.";
        // Optional: Redirect to cart page or another page after successful addition
        // header("Location: myCart.php");
        // exit();
    } else {
        echo "Error adding pet to cart: " . $conn->error;
    }
}

// Close database connection
$conn->close();