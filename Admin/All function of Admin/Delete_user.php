<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Ensure ID is an integer to prevent SQL injection

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Delete related rows in the `cart` table
        $sql_cart = "DELETE FROM cart WHERE user_id = ?";
        $stmt_cart = $conn->prepare($sql_cart);
        if (!$stmt_cart) {
            throw new Exception($conn->error);
        }
        $stmt_cart->bind_param("i", $id);
        $stmt_cart->execute();
        $stmt_cart->close();

        // Delete related rows in the `petdetails` table
        $sql_petdetails = "DELETE FROM petdetails WHERE user_id = ?";
        $stmt_petdetails = $conn->prepare($sql_petdetails);
        if (!$stmt_petdetails) {
            throw new Exception($conn->error);
        }
        $stmt_petdetails->bind_param("i", $id);
        $stmt_petdetails->execute();
        $stmt_petdetails->close();

        // Delete related rows in the `buy_form_data` table
        $sql_buy_form = "DELETE FROM buy_form_data WHERE user_id = ?";
        $stmt_buy_form = $conn->prepare($sql_buy_form);
        if (!$stmt_buy_form) {
            throw new Exception($conn->error);
        }
        $stmt_buy_form->bind_param("i", $id);
        $stmt_buy_form->execute();
        $stmt_buy_form->close();

        // Delete the user record
        $sql_user = "DELETE FROM users WHERE id = ?";
        $stmt_user = $conn->prepare($sql_user);
        if (!$stmt_user) {
            throw new Exception($conn->error);
        }
        $stmt_user->bind_param("i", $id);
        $stmt_user->execute();

        if ($stmt_user->affected_rows === 0) {
            throw new Exception("No user found with ID: " . $id);
        }

        $stmt_user->close();

        // Commit the transaction
        $conn->commit();

        // Use a session or another method to flash the message
        $_SESSION['message'] = "User and related details deleted successfully.";
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        error_log("Error deleting user: " . $e->getMessage());
        $_SESSION['message'] = "Error deleting user: " . $e->getMessage();
    }
} else {
    $_SESSION['message'] = "No user ID provided.";
}

// Close the connection
$conn->close();

// Redirect back to the admin dashboard
header("Location: user_details.php");
exit();
