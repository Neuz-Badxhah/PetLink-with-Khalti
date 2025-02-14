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



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_dashboard.css">
    <link rel="web icon" href="../Images/logo.jpg">

    <title>Admin panel</title>
    <style>
    /* Navbar styling */
    .navbaritem {
        background-color: #007bff;
        /* Background color for the navbar */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        /* Box shadow for navbar */
        overflow: hidden;
    }

    /* Navbar items */
    .navitem {
        list-style-type: none;
        /* Remove bullet points */
        margin: 0;
        padding: 0;
        display: flex;
        /* Display items in a row */
    }

    /* Navbar links */
    .navitem>li {
        position: relative;
    }

    .navitem>li>a {
        display: block;
        color: white;
        text-align: center;
        padding: 14px 20px;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .navitem>li>a:hover {
        background-color: #0056b3;
        /* Darker shade of blue on hover */
    }



    /* Additional styling for active and logout links */
    .navitem>li>a.active {
        background-color: #0056b3;
        /* Active link color */
    }

    .navitem>li>a.logout {
        background-color: #dc3545;
        /* Logout link color */
    }

    .navitem>li>a.logout:hover {
        background-color: #c82333;
        /* Darker shade of red on hover */
    }


    /* Home section styling */
    .home-section {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        text-align: center;
    }

    .home-section h2 {
        margin-bottom: 20px;
    }

    .home-section p {
        font-size: 18px;
        margin-bottom: 20px;
    }

    .button-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 20px;
    }

    .button {
        padding: 10px 20px;
        font-size: 16px;
        text-decoration: none;
        color: #fff;
        background-color: #007bff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .button:hover {
        background-color: #0056b3;
    }
    </style>

</head>

<body>

    <div class="navbaritem">
        <ul class="navitem">
            <li class="dropdown"><a href="#">Home </a></li>
            <li><a href="user_details.php">User Details</a></li>
            <li><a href="pet_details.php">Pet Details</a></li>
            <li><a href="buy_details.php">Buy Details</a></li>

            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="home-section">
        <h2>Welcome to the Pet Link Admin Panel</h2>
        <p>Hello, <?php echo $_SESSION['username']; ?>!</p>
        <div class="button-container">
            <a class="button" href="user_details.php">User Details</a>
            <a class="button" href="pet_details.php">Pet
                Details</a>
            <a class="button" href="buy_details.php">Buy Details</a>
        </div>
    </div>
</body>

</html>