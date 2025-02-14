<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'config.php';

if (isset($_GET['id'])) {
    $pet_id = $_GET['id'];

    if ($stmt = $conn->prepare("UPDATE petdetails SET status='approved' WHERE id=?")) {
        $stmt->bind_param("i", $pet_id);
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: pet_details.php");
            exit();
        } else {
            // Execution failed
            echo "Error executing query: " . $stmt->error;
            $stmt->close();
        }
    } else {
        // Preparation failed
        echo "Error preparing query: " . $conn->error;
    }
} else {
    echo "ID not set";
}

$conn->close();