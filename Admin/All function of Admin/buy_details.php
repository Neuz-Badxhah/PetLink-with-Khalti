<?php
// Start session to access session variables
session_start();

if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}

// Check if admin session is set
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}


// Turn off all error reporting
error_reporting(0);

// Report simple running errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Reporting E_NOTICE can be good too (to report uninitialized
// variables or catch variable name misspellings ...)
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE);

// Report all PHP errors
error_reporting(E_ALL);

// Report all PHP errors
error_reporting(-1);

// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);


// Database connection details
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="web icon" href="../Images/logo.jpg">

    <title>Admin Panel Purchase Details</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #4CAF50;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    /* Action links styling */
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

    .action-links-approve {
        background-color: #28a745;
        color: white;
    }

    .action-links-approve:hover {
        background-color: #218838;
    }

    .action-links-reject {
        background-color: #ffc107;
        color: black;
    }

    .action-links-reject:hover {
        background-color: #e0a800;
    }

    .action-links-delete {
        background-color: #dc3545;
        color: white;
    }

    .action-links-delete:hover {
        background-color: #c82333;
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
    <h1>Buy Form Submissions</h1>
    <button type="button" onclick="window.location.href='Admin_Dashboard.php';">Home</button>
    <button type="button" class="logout" onclick=" window.location.href=' logout.php' ;">Log Out</button>

    <table>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Pet ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Address</th>
            <th>Occupation</th>
            <th>Owned Pets</th>
            <th>Pet Types</th>
            <th>Ownership Duration</th>
            <th>Reason No Pet</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php
        // Select all entries from buy_form_data
        $sql = "SELECT * FROM buy_form_data";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['user_id'] . "</td>";
                echo "<td>" . $row['pet_id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['contact'] . "</td>";
                echo "<td>" . $row['address'] . "</td>";
                echo "<td>" . $row['occupation'] . "</td>";
                echo "<td>" . $row['owned_pets'] . "</td>";
                echo "<td>" . $row['pet_types'] . "</td>";
                echo "<td>" . $row['ownership_duration'] . "</td>";
                echo "<td>" . $row['reason_no_pet'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>";
                echo "<a class='action_links action-links-approve' href='approve_buy.php?id=" . htmlspecialchars($row["id"]) . "'>Approve</a>";
                echo "<a class='action_links action-links-reject' href='reject_buy.php?id=" . htmlspecialchars($row["id"]) . "'>Reject</a>";
                echo "<a class='action_links action-links-delete' href='delete_buy.php?id=" . htmlspecialchars($row["id"]) . "' onclick=\"return confirm('Are you sure you want to delete this pet?');\">Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='15'>No submissions found.</td></tr>";
        }
        ?>
    </table>
</body>

</html>