<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pet_link_project";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pet_id'])) {
    $pet_id = $_POST['pet_id'];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to delete pet listing
    $sql_delete = "DELETE FROM PetDetails WHERE id=?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $pet_id);

    // Execute the delete statement
    if ($stmt_delete->execute()) {
        // Deletion successful
        echo "<script>alert('Pet listing deleted successfully');</script>";
        echo "<script>window.location.href = 'dashboard.php';</script>";
    } else {
        // Deletion failed
        echo "<script>alert('Error deleting pet listing');</script>";
    }

    $stmt_delete->close();
    $conn->close();
    exit();
} else {
    // If pet_id is not set, redirect back to the page or handle error
    header("Location Dashboard.php");
    exit();
}
