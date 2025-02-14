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

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id']; // Ensure user_id is obtained from session
    $username = $_POST['username'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Handle profile picture upload
    if (!empty($_FILES['profile-picture']['name'])) {
        $profile_picture = $_FILES['profile-picture'];
        $file_name = $_FILES['profile-picture']['name'];
        $file_tmp = $_FILES['profile-picture']['tmp_name'];
        $file_size = $_FILES['profile-picture']['size'];
        $file_error = $_FILES['profile-picture']['error'];

        // Check if file uploaded without errors
        if ($file_error === 0) {
            // Generate unique file name to prevent overwriting existing files
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $unique_file_name = uniqid('profile_', true) . '.' . $file_ext;

            // Move uploaded file to desired location
            $upload_dest = 'uploads/' . $unique_file_name;
            if (move_uploaded_file($file_tmp, $upload_dest)) {
                // Update profile picture in database
                $update_picture_sql = "UPDATE users SET profile_picture='$unique_file_name' WHERE id='$user_id'";
                if ($conn->query($update_picture_sql) === FALSE) {
                    echo "Error updating profile picture: " . $conn->error;
                    exit();
                }
            } else {
                echo "Error uploading file.";
                exit();
            }
        } else {
            echo "Error: " . $file_error;
            exit();
        }
    }

    // Update other profile information
    $update_sql = "UPDATE users SET username='$username', address='$address', contact='$contact', email='$email'";

    // Update password if provided and matches confirm password
    if (!empty($password) && $password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_sql .= ", password='$hashed_password'";
    }

    $update_sql .= " WHERE id='$user_id'";

    if ($conn->query($update_sql) === TRUE) {
        // Redirect to avoid duplicate form submission
        header("Location: Dashboard.php");
        exit(); // Ensure script termination after header redirection
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <link rel="web icon" href="../Images/logo.jpg">

    <title>Edit Profile</title>
</head>

<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact:</label>
                <input type="text" name="contact" id="contact">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm password:</label>
                <input type="password" name="confirm-password" id="confirm-password">
            </div>
            <div class="form-group">
                <label for="profile-picture">Profile Picture:</label>
                <input type="file" name="profile-picture" id="profile-picture">
            </div>
            <button type="submit">Update Profile</button>
            <button type="button" onclick="window.location.href='Dashboard.php'">Cancel</button>
        </form>
    </div>
    <script src="register.js"></script>
</body>

</html>