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

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "No user found";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profile.css">
    <link rel="web icon" href="../Images/logo.jpg">

    <title>Edit Profile</title>
    <style>
        .cancel-button {
            background-color: #3f3f3f;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 12px;
        }

        .cancel-button:hover {
            background-color: #1b1b1b;
        }
    </style>
    <script>
        function validateForm() {
            let username = document.getElementById("username").value;
            let address = document.getElementById("address").value;
            let email = document.getElementById("email").value;
            let contact = document.getElementById("contact").value;
            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("confirm-password").value;

            if (username === "") {
                alert("Username must be filled out");
                exit();
                return false;
            }
            if (address === "") {
                alert("Address must be filled out");
                exit();
                return false;
            }
            if (contact === "") {
                alert("Contact must be filled out");
                return false;
                exit();
            }
            if (!/^\d{10}$/.test(contact)) {
                alert("Contact must be a 10-digit number");
                exit();
                return false;
            }
            if (email === "") {
                alert("Email must be filled out");
                return false;
                exit();
            }

            if (password !== "" && password !== confirmPassword) {
                alert("Passwords do not match");
                return false;
                exit();
            }
            return true;
        }
    </script>
</head>

<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <form action="Update_profile.php" method="post" enctype="multipart/form-data" onsubmit="validateForm()">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <div class="form-group">
                <label for="profile-picture">Profile Picture:</label>
                <input type="file" id="profile-picture" name="profile-picture">
                <?php if (!empty($user['profile_picture'])) : ?>
                    <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
                <?php else : ?>
                    <img src="../Images/Default profile.jpg" alt="Default Profile Picture" width="100px">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" 7 value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact:</label>
                <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($user['contact']); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm New Password:</label>
                <input type="password" id="confirm-password" name="confirm-password">
            </div>
            <button type="submit">Update Profile</button>
            <button class="delete-button" href='Delete_User.php?id=<?php echo htmlspecialchars($user["id"]); ?>' onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
            <button type="button" class="cancel-button" onclick="window.location.href='Dashboard.php'">Cancel</button>
        </form>
    </div>

</body>

</html>