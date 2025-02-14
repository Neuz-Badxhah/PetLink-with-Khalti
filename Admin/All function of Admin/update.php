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
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="web icon" href="../Images/logo.jpg">

    <title>Edit User</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        padding: 20px;
    }

    form {
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
    }

    label {
        display: block;
        margin-bottom: 8px;
    }

    input[type="text"],
    input[type="email"] {
        width: calc(100% - 20px);
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }

    button[type="submit"] {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 12px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
        margin-right: 10px;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }

    button[type="submit"]:focus {
        outline: none;
    }

    .cancel-link {
        background-color: gray;
        color: #fff;
        text-decoration: none;
        padding: 12px 20px;
        border-radius: 4px;
    }

    .cancel-link:hover {
        background-color: #333;
    }
    </style>



</head>

<body>
    <form action="update_user.php" method="post" onsubmit="return validateForm()">
        <h2>Edit User</h2>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($row['username']); ?>" required>
        <label for="address">Address:</label>
        <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($row['address']); ?>"
            required>
        <label for="contact">Contact:</label>
        <input type="text" name="contact" id="contact" value="<?php echo htmlspecialchars($row['contact']); ?>"
            required>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
        <label for="password">Password:</label>
        <input type="text" name="password" id="password" value="">
        <button type="submit" name="update_user">Save Changes</button>
        <a class="cancel-link" href="user_details.php">Cancel</a>
    </form>
    <script>
    function validateForm() {
        const name = document.getElementById('name').value.trim();
        const address = document.getElementById('address').value.trim();
        const contact = document.getElementById('contact').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();

        if (!name || !address || !contact || !email) {
            alert("All fields are required.");
            return false;
        }

        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email)) {
            alert("Invalid email address.");
            return false;
        }

        const contactPattern = /^[0-9]{10}/;
        if (!contactPattern.test(contact)) {
            alert("Invalid contact number. It should be only  10 values in number.");
            return false;
        }
    }
    </script>
</body>

</html>