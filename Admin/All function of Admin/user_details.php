<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']); // Clear the message after displaying
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

// Update user data if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_user"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $address = $_POST["address"];
    $contact = $_POST["contact"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "../User/uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
        $photo = basename($_FILES["profile_picture"]["name"]);
    } else {
        $photo = $_POST["current_photo"];
    }

    $sql_update = "UPDATE users SET username=?, address=?, contact=?, email=?, password=?, profile_picture=? WHERE id=?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssssssi", $name, $address, $contact, $email, $password, $photo, $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch users data
$sql_users = "SELECT * FROM users";
$result_users = $conn->query($sql_users);

// Fetch pet data
$sql_pets = "SELECT * FROM petdetails";
$result_pets = $conn->query($sql_pets);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="web icon" href="../Images/logo.jpg">

    <title>User Details</title>
    <style>
    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table th,
    table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    table td img {
        display: block;
        margin: 0 auto;
        max-width: 100px;
        height: auto;
    }

    /* Styles for user action links */
    .action_links {
        display: inline-block;
        margin-right: 5px;
        padding: 5px 10px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        transition: background-color 0.3s ease, transform 0.3s ease;
        text-align: center;
        width: 80px;
    }

    .action-links-update {
        background-color: #007bff;
        color: white;

    }

    .action-links-update:hover {
        background-color: #0056b3;
        color: white;
        transform: scale(1.05);
    }

    .action-links-delete {
        color: white;

        background-color: #dc3545;
    }

    .action-links-delete:hover {
        color: white;

        background-color: #c82333;
        transform: scale(1.05);
    }


    button {
        background-color: #007bff;
        /* Blue background */
        color: white;
        /* White text */
        border: none;
        /* Remove borders */
        padding: 10px 20px;
        /* Add some padding */
        text-align: center;
        /* Center the text */
        text-decoration: none;
        /* Remove underline from links */
        display: inline-block;
        /* Make it inline */
        font-size: 16px;
        /* Font size */
        margin: 5px 10px;
        /* Add some space between buttons */
        border-radius: 5px;
        /* Rounded corners */
        cursor: pointer;
        /* Pointer cursor on hover */
        transition: background-color 0.3s ease, transform 0.3s ease;
        /* Smooth transitions */
    }

    /* Home Button */
    button:hover {
        background-color: #0056b3;
        /* Darker blue on hover */
        transform: scale(1.05);
        /* Slightly larger on hover */
    }

    /* Log Out Button */
    button.logout {
        background-color: #dc3545;
        /* Red background for logout */
    }

    button.logout:hover {
        background-color: #c82333;
        /* Darker red on hover */
    }
    </style>
</head>

<body>
    <div class="user-section">

        <h2>User Data</h2>

        <button type="button" onclick="window.location.href='Admin_Dashboard.php';">Home</button>
        <button type="button" class="logout" onclick="window.location.href='logout.php';">Log Out</button>



        <table id="user" border="1">
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result_users->num_rows > 0) {
                while ($row = $result_users->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                    echo "<td>";
                    if (!empty($row["profile_picture"])) {
                        echo '<img src="../User/uploads/' . htmlspecialchars($row["profile_picture"]) . '" alt="Profile Picture" width="50">';
                    } else {
                        echo "No photo";
                    }
                    echo "</td>";
                    echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["address"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["contact"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    echo "<td>";
                    echo "<a class='action_links action-links-update' href='update.php?id=" . htmlspecialchars($row["id"]) . "'>Edit User</a>";
                    echo "<a class='action_links action-links-delete' href='Delete_User.php?id=" . htmlspecialchars($row["id"]) . "' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>";

                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No users found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>