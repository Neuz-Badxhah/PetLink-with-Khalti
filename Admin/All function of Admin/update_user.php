<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'config.php'; // Include your database connection file

// Update user data if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_user"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $address = $_POST["address"];
    $contact = $_POST["contact"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Encrypt the password

    $sql = "UPDATE users SET username=?, address=?, contact=?, email=?, password=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name, $address, $contact, $email, $password, $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['update_message'] = 'User data updated successfully!';
    header("Location: user_details.php");
    exit();
}

// Retrieve user data to display in the form
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT * FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
}
