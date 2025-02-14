<?php
session_start();

// Database connection settings
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id']; // Get user ID from the hidden input field

    // Handle file uploads
    $target_dir = "uploads/";
    $image_target_file = $target_dir . basename($_FILES["pet-image"]["name"]);
    $video_target_file = $target_dir . basename($_FILES["pet-video"]["name"]);

    // Move uploaded files to the target directory
    if (
        move_uploaded_file($_FILES["pet-image"]["tmp_name"], $image_target_file) &&
        move_uploaded_file($_FILES["pet-video"]["tmp_name"], $video_target_file)
    ) {

        // Prepare and bind parameters for the SQL statement
        $stmt = $conn->prepare("INSERT INTO PetDetails (user_id, pet_image, pet_video, pet_name, pet_age, pet_size, pet_type, pet_breed, health_status, description, price, upload_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("isssisssssi", $user_id, $image_target_file, $video_target_file, $pet_name, $pet_age, $pet_size, $pet_type, $pet_breed, $health_status, $description, $price);

        // Set parameters
        $pet_name = $_POST['pet-name'];
        $pet_age = $_POST['pet-age'];
        $pet_size = $_POST['pet-size'];
        $pet_type = isset($_POST['pet-type']) ? $_POST['pet-type'] : null; // Handle if pet-type is not set
        $pet_breed = $_POST['pet-breed'];
        $health_status = isset($_POST['health-status']) ? $_POST['health-status'] : null; // Handle optional health status
        $description = $_POST['description'];
        $price = $_POST['price'];

        // Execute SQL statement
        if ($stmt->execute()) {
            echo "<script>alert('Congratulations, your submission is complete.')</script>";
            echo "<script>window.location.href = '../User/dashboard.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Sorry, there was an error uploading your files.";
    }
}

// Close connection
$conn->close();
