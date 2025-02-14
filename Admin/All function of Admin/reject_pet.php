<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';

if (isset($_GET['id'])) {
    $pet_id = $_GET['id'];
    $sql = "UPDATE petdetails SET status='rejected' WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pet_id);
    $stmt->execute();
    $stmt->close();
}
$conn->close();
header("Location: pet_details.php");
exit();
