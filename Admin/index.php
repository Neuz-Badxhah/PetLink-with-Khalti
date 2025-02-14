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
if (isset($_POST['username']) && isset($_POST['password'])) {
    // Initialize variables to store user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Debugging: Print submitted values
    echo "Submitted Username: $username <br>";
    echo "Submitted Password: $password <br>";

    // Hardcoded admin credentials (for demo purposes)
    $admin_username = 'admin';
    $admin_password = 'admin';

    // Check if input matches hardcoded admin credentials
    if ($username === $admin_username && $password === $admin_password) {
        // Admin login successful
        $_SESSION['username'] = $username; // Store username in session variable
        echo "Session username: " . $_SESSION['username'] . "<br>";

        // Redirect to admin dashboard
        header("Location: All function of admin/Admin_Dashboard.php");
        exit();
    } else {
        // Login failed
        echo "Invalid username or password <a href='index.php'>Try again</a>";
    }
}

// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="web icon" href="../Images/logo.jpg">

    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f5f5f5;
    }

    .container {
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 350px;
        width: 100%;
    }

    h2 {
        margin-bottom: 20px;
        text-align: center;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="password"] {
        width: 90%;
        padding: 8px;
        box-sizing: border-box;
    }

    button {
        width: 40%;
        padding: 10px;
        margin: 5px;
        background-color: #007bff;
        border: none;
        color: white;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }

    button[type="button"]:hover {
        background-color: #474f57;
    }

    form p a {
        text-decoration: none;
        color: #007bff;
        margin-left: 10px;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Admin Login</h2>
        <form action="#" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <button type="button" onclick="window.location.href='../index.php'">Cancel</button>
        </form>
    </div>
</body>

</html>