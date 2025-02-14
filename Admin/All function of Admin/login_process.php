<?php
session_start();

// Assuming database connection details
$servername = "localhost";
$username_db = "root"; // Replace with your database username
$password_db = ""; // Replace with your database password
$dbname = "pet_link_project";

// Create connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if username and password were submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['error_message'] = 'Please fill out both username and password fields.';
        header("Location: index.php");
        exit();
    }

    // Hardcoded admin credentials (for demo purposes)
    $admin_username = 'admin';
    $admin_password = 'admin';

    // Check if input matches hardcoded admin credentials
    if ($username === $admin_username && $password === $admin_password) {
        // Admin login successful
        $_SESSION['user_id'] = 1; // Assign a user ID to the session

        // Redirect to admin dashboard
        header("Location: All function of admin/Admin_Dashboard.php");
        exit();
    } else {
        // Login failed
        $_SESSION['error_message'] = 'Invalid username or password.';
        header("Location: index.php");
        exit();
    }
}

// Close database connection
$conn->close();
