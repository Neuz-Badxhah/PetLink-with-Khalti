<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link rel="web icon" href="../Images/logo.jpg">

    <title>Login Form</title>
</head>

<body>

    <div class="container">
        <h2>Login to Pet Link</h2>
        <form action="#" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" onclick="window.location.href='../User_panel.php'">Login</button>
            <button type="button" onclick="window.location.href='../index.php'">Cancel</button>
        </form>
        <p>Don't have an account? <a href=" register.php">Sign up here</a></p>
    </div>
    <?php
    session_start();
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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: ../User/Dashboard.php");
                exit();
            } else {
                echo "<script>alert('Invalid email and password');</script>";
            }
        } else {
            echo "<script>alert('No user find this email.');</script>";
        }
    }

    $conn->close();
    ?>