<?php
// Start session to access session variables
session_start();

// Check if user session is set
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
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

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch the user ID from the session
$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch details of the buyer and the pet
    $sql = "
        SELECT b.*, u.email AS buyer_email, u.username AS buyer_name, p.user_id AS owner_id, p.pet_name
        FROM buy_form_data b
        JOIN users u ON b.user_id = u.id
        JOIN petdetails p ON b.pet_id = p.id
        WHERE b.id = ? AND b.user_id = ?
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Fetch owner's email
        $owner_id = $row['owner_id'];
        $sql_owner = "SELECT email, username FROM users WHERE id = ?";
        $stmt_owner = $conn->prepare($sql_owner);
        if (!$stmt_owner) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt_owner->bind_param("i", $owner_id);
        $stmt_owner->execute();
        $result_owner = $stmt_owner->get_result();

        if ($result_owner->num_rows > 0) {
            $owner = $result_owner->fetch_assoc();
            $owner_email = $owner['email'];
            $owner_name = $owner['username'];
            $pet_name = $row['pet_name'];

            // Approve the buy request
            $sql_update = "UPDATE buy_form_data SET status = 'approved' WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            if (!$stmt_update) {
                die("Prepare failed: " . $conn->error);
            }

            $stmt_update->bind_param("i", $id);
            if (!$stmt_update->execute()) {
                die("Execute failed: " . $stmt_update->error);
            }

            // Send email to the buyer
            $buyer_email = $row['buyer_email'];
            $buyer_name = $row['buyer_name'];
            $subject_buyer = "Pet Purchase Approval - PetLink";
            $message_buyer = "Dear $buyer_name,\n\nYour purchase request for the pet named '$pet_name' has been accepted.\n\nThank you,\nPetLink Team";
            $headers = "From: neuzbadxhah@gmail.com";

            if (!mail($buyer_email, $subject_buyer, $message_buyer, $headers)) {
                echo "Failed to send email to buyer.";
            }

            // Send email to the owner
            $subject_owner = "Pet Purchase Approval - PetLink";
            $message_owner = "Dear $owner_name,\n\nThe purchase request for your pet named '$pet_name' has been accepted.\nWe will notify you for meeting.\n\nThank you,\nPetLink Team";

            if (!mail($owner_email, $subject_owner, $message_owner, $headers)) {
                echo "Failed to send email to owner.";
            }

            // Redirect back to the buyer dashboard with success message
            $_SESSION['message'] = "Purchase request accepted and emails sent.";
            header("Location: buy_details.php");
            exit();
        } else {
            // Owner not found
            echo "Owner details not found.";
        }
    } else {
        // No record found
        echo "No record found.";
    }
} else {
    // No ID specified
    echo "Invalid request.";
}

$conn->close();
