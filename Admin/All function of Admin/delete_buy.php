<?php
// Start session to access session variables
session_start();

// Check if admin session is set
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

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

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch details of the buyer and the pet
    $sql = "
        SELECT b.*, u.email AS buyer_email, u.username AS buyer_name, p.user_id AS owner_id, p.pet_name
        FROM buy_form_data b
        JOIN users u ON b.user_id = u.id
        JOIN petdetails p ON b.pet_id = p.id
        WHERE b.id = ?
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
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

            // Delete the buy request
            $sql_delete = "DELETE FROM buy_form_data WHERE id = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            if (!$stmt_delete) {
                die("Prepare failed: " . $conn->error);
            }

            $stmt_delete->bind_param("i", $id);
            $stmt_delete->execute();

            // Send email to the buyer
            $buyer_email = $row['buyer_email'];
            $buyer_name = $row['buyer_name'];
            $subject_buyer = "Pet Purchase Request Deleted - PetLink";
            $message_buyer = "Dear $buyer_name,\n\nWe regret to inform you that your purchase request for the pet named '$pet_name' has been deleted from our system.\n\nThank you,\nPetLink Team";
            $headers = "From: neuzbadxhah@gmail.com";

            if (!mail($buyer_email, $subject_buyer, $message_buyer, $headers)) {
                echo "Failed to send email to buyer.";
            }

            // Send email to the owner
            $subject_owner = "Pet Purchase Request Deleted - PetLink";
            $message_owner = "Dear $owner_name,\n\nThe purchase request for your pet named '$pet_name' has been deleted from our system.\n\nThank you,\nPetLink Team";

            if (!mail($owner_email, $subject_owner, $message_owner, $headers)) {
                echo "Failed to send email to owner.";
            }

            // Redirect back to the admin panel with success message
            $_SESSION['message'] = "Purchase request deleted and emails sent.";
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
