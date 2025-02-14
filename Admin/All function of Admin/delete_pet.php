<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

if (isset($_GET['id'])) {
    $pet_id = $_GET['id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Delete related cart records
        $sql_cart = "DELETE FROM cart WHERE pet_id=?";
        $stmt_cart = $conn->prepare($sql_cart);
        if (!$stmt_cart) {
            throw new Exception($conn->error);
        }
        $stmt_cart->bind_param("i", $pet_id);
        $stmt_cart->execute();
        $stmt_cart->close();

        // Delete the petdetails record
        $sql_petdetails = "DELETE FROM petdetails WHERE id=?";
        $stmt_petdetails = $conn->prepare($sql_petdetails);
        if (!$stmt_petdetails) {
            throw new Exception($conn->error);
        }
        $stmt_petdetails->bind_param("i", $pet_id);
        $stmt_petdetails->execute();
        $stmt_petdetails->close();

        // Commit the transaction
        $conn->commit();
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        $_SESSION['message'] = "Error deleting pet: " . $e->getMessage();
        header("Location: pet_details.php");
        exit();
    }
}

$conn->close();
header("Location: pet_details.php");
exit();
