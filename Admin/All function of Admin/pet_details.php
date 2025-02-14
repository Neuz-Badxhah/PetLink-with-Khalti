<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
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
// Fetch pet data
$sql_pets = "SELECT * FROM petdetails";
$result_pets = $conn->query($sql_pets);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="web icon" href="../../Images/logo.jpg">

    <title>Pet Detailst</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

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

    <div class="container">
        <h2>Pet Data</h2>
        <button type="button" onclick="window.location.href='Admin_Dashboard.php';">Home</button>
        <button type="button" class="logout" onclick="window.location.href='logout.php';">Log Out</button>
        <table id="pet" border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Age(in yrs)</th>
                    <th>Size</th>
                    <th>Type</th>
                    <th>Breed</th>
                    <th>Health Status</th>
                    <th>Description</th>
                    <th>Price($)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_pets->num_rows > 0) {
                    while ($row = $result_pets->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                        echo "<td><img src='purchase/uploads/" . htmlspecialchars($row["pet_image"]) . "' alt='Pet Image' width='50'></td>";
                        echo "<td>" . htmlspecialchars($row["pet_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["pet_age"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["pet_size"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["pet_type"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["pet_breed"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["health_status"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["price"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                        echo "<td>";
                        echo "<a class='action_links action-links-approve' href='approve_pet.php?id=" . htmlspecialchars($row["id"]) . "'>Approve</a>";
                        echo "<a class='action_links action-links-reject' href='reject_pet.php?id=" . htmlspecialchars($row["id"]) . "'>Reject</a>";
                        echo "<a class='action_links action-links-delete' href='delete_pet.php?id=" . htmlspecialchars($row["id"]) . "' onclick=\"return confirm('Are you sure you want to delete this pet?');\">Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12'>No pets found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>