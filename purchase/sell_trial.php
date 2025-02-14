<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login Register/login.php");
    exit();
}
$user_id = $_SESSION['user_id']; // Get user ID from session
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Your Pet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }

        .pet-container {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            max-width: 600px;
            height: 100vh;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .pet-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .pet-container input[type="text"],
        .pet-container input[type="number"],
        .pet-container textarea,
        .pet-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .pet-container input[type="file"] {
            margin-top: 5px;
        }

        .pet-container textarea {
            resize: vertical;
            /* Allow vertical resizing of textarea */
        }

        .pet-container select {
            padding: 8px;
        }

        .form-buttons {
            margin: 0 auto;
            display: flex;
            gap: 20px;

        }

        .form-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        .form-buttons button[type="submit"] {
            background-color: #28a745;
            color: white;
        }

        .form-buttons button[type="reset"] {
            background-color: #ffc107;
            color: white;
        }

        .form-buttons button:hover {
            opacity: 0.8;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .pet-container {
                padding: 15px;
            }

            .pet-container input[type="text"],
            .pet-container input[type="number"],
            .pet-container textarea,
            .pet-container select {
                font-size: 14px;
            }
        }

        .profile {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
            background-color: blue;
            color: white;
        }

        .profile:hover {
            opacity: 0.8;

        }
    </style>
    <script>
        function validateForm() {
            var price = document.getElementById("price").value;
            if (price < 0) {
                alert("Price cannot be negative.");
                exit();
                return false;
            }
            return confirm('Please confirm your pet details?');
        }
    </script>
    </script>
</head>

<body>
    <div class="pet-container">
        <h2>Sell Your Pet</h2>
        <form action="process_sell_pet.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

            <label for="pet-image">Upload Image:</label>
            <input type="file" name="pet-image" id="pet-image" accept="image/*" required>

            <label for="pet-video">Upload Short Video (less than 5 MB):</label>
            <input type="file" name="pet-video" id="pet-video" accept="video/*" required>

            <label for="pet-name">Name:</label>
            <input type="text" name="pet-name" id="name" required>

            <label for="pet-age">Age (in Yrs):</label>
            <input type="text" name="pet-age" id="age" required>

            <label for="pet-size">Size:</label>
            <input type="text" name="pet-size" id="pet-size" required>

            <label for="petSelect">Choose a pet:</label>
            <select id="petSelect" name="pet-type">
                <option value="cat">Cat</option>
                <option value="dog">Dog</option>
                <option value="other">Other</option>
            </select>

            <label for="pet-breed">Breed:</label>
            <input type="text" name="pet-breed" id="breed" required>

            <label for="health-status">Health Status:</label>
            <input type="text" name="health-status" id="health-status">

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" cols="50" maxlength="100" required></textarea>

            <label for="price">Price(Rs):</label>
            <input type="number" name="price" id="price">

            <div class="form-buttons">
                <button type="submit" onclick=validateForm()>Submit</button>
                <button type="reset" onclick="return confirm('Do you want to reset your form?')">Reset</button>
                <button type="button" class="profile" onclick="window.location.href='../User/Dashboard.php';">Profile</button>

            </div>
        </form>
    </div>
</body>

</html>