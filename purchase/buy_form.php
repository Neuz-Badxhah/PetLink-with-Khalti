<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$pet_id = isset($_GET['pet_id']) ? $_GET['pet_id'] : null;

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

// Retrieve pet details and price based on the pet_id
$pet_price = null;
if ($pet_id) {
    $sql = "SELECT price ,pet_name FROM petdetails WHERE id='$pet_id' AND status='approved'";  // Assuming pets have a 'status' column to filter approved pets
    $pet_result = $conn->query($sql);
    if ($pet_result->num_rows > 0) {
        $pet = $pet_result->fetch_assoc();
        $pet_price = $pet['price'];
        $pet_name = $pet['pet_name'];
    } else {
        echo "No pet found with the given ID";
        exit();
    }
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = $user['username'];
    $email = $user['email'];
    $contact = $user['contact'];
    $address = $user['address'];
} else {
    echo "No user found";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Link - Buy Form</title>
    <link rel="stylesheet" href="../Css/buy_form.css">
    <style>
        .form-group {
            margin-bottom: 15px;
        }

        .radio-group {
            display: flex;
            gap: 10px;
        }

        .submit-btn {
            margin-right: 10px;
        }
    </style>
</head>

<body>
   
<form class="purchase_form" action="../Main/payment_processor.php" method="POST">
        <!-- Add a hidden input for pet_id -->
        <input type="hidden" name="pet_id" value="<?php echo htmlspecialchars($pet_id); ?>">
        <input type="hidden" name="pet_name" value="<?php echo htmlspecialchars($pet_name); ?>">

        <!-- User Information -->
        <h2><big>Purchase Form</big></h2>
        <h2>User Information</h2>
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($username); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="form-group">
            <label for="contact">Contact Number</label>
            <input type="tel" id="contact" name="contact" value="<?php echo htmlspecialchars($contact); ?>" required>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
        </div>
        <div class="form-group">
            <label for="pet_price">Pet Price</label>
            <input type="number" id="number" name="number" value="<?php echo htmlspecialchars($pet_price); ?>" required>
        </div>
        <!-- Work Information -->
        <h2>Work Information</h2>
        <div class="form-group">
            <label>Do you work?</label>
            <div class="radio-group">
                <label><input type="radio" name="do_you_work" value="yes" onclick="toggleWorkDetails(true)"> Yes</label>
                <label><input type="radio" name="do_you_work" value="no" onclick="toggleWorkDetails(false)"> No</label>
            </div>
        </div>
        <div class="form-group" id="work_details" style="display: none;">
            <label for="occupation">What do you do?</label>
            <input type="text" id="occupation" name="occupation">
        </div>


        <script>
            function toggleWorkDetails(show) {
                var workDetails = document.getElementById('work_details');
                if (show) {
                    workDetails.style.display = 'block';
                } else {
                    workDetails.style.display = 'none';
                }
            }
        </script>


        <!-- Past Pet History -->
        <h2>Past Pet History</h2>
        <div class="form-group">
            <label for="owned_pets">Have you owned pets before?</label>
            <div class="radio-group">
                <label><input type="radio" name="owned_pets" value="yes" onclick="showPastPetHistory(true)"> Yes</label>
                <label><input type="radio" name="owned_pets" value="no" onclick="showPastPetHistory(false)"> No</label>
            </div>
        </div>
        <div class="form-group" id="pet_types_section" style="display: none;">
            <label for="pet_types">Types of pets owned</label>
            <input type="text" id="pet_types" name="pet_types">
        </div>
        <div class="form-group" id="ownership_duration_section" style="display: none;">
            <label for="ownership_duration">Duration of ownership</label>
            <input type="text" id="ownership_duration" name="ownership_duration">
        </div>
        <div class="form-group" id="reason_no_pet_section" style="display: none;">
            <label for="reason_no_pet">Reason for no longer having the pet (if applicable)</label>
            <textarea id="reason_no_pet" name="reason_no_pet"></textarea>
        </div>
        <!-- Add the Khalti script -->
        <script src="https://khalti.com/static/khalti-checkout.js"></script>

        <div class="form-group" id="payment">
            <label for="payment_method">Pay for submit</label>
            <button type="submit" name = "pay_btn" class="submit-btn">
                Proceed to Payment
            </button>
        </div>

        </div>



    
        <button type="button" class="cancel-btn" onclick="window.location.href='buy.php'">Cancel</button>
        <button type="button" class="back-btn" onclick="window.location.href='../User/Dashboard.php'">Back</button>


    </form>

    <script>
        function showPastPetHistory(show) {
            var petTypesSection = document.getElementById('pet_types_section');
            var ownershipDurationSection = document.getElementById('ownership_duration_section');
            var reasonNoPetSection = document.getElementById('reason_no_pet_section');

            if (show) {
                petTypesSection.style.display = 'block';
                ownershipDurationSection.style.display = 'block';
                reasonNoPetSection.style.display = 'block';
            } else {
                petTypesSection.style.display = 'none';
                ownershipDurationSection.style.display = 'none';
                reasonNoPetSection.style.display = 'none';
            }
        }
    </script>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $pet_id = isset($_POST['pet_id']) ? $_POST['pet_id'] : null;
    $pet_name = isset($_POST['pet_name']) ? $_POST['pet_name'] : null;
    $user_id = $_SESSION['user_id'];
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $contact = isset($_POST['contact']) ? $_POST['contact'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $do_you_work = isset($_POST['do_you_work']) ? $_POST['do_you_work'] : 'no';
    $occupation = $do_you_work === 'yes' ? (isset($_POST['occupation']) ? $_POST['occupation'] : '') : null;
    $owned_pets = isset($_POST['owned_pets']) ? $_POST['owned_pets'] : 'no';
    $pet_types = $owned_pets === 'yes' ? (isset($_POST['pet_types']) ? $_POST['pet_types'] : '') : null;
    $ownership_duration = $owned_pets === 'yes' ? (isset($_POST['ownership_duration']) ? $_POST['ownership_duration'] : '') : null;
    $reason_no_pet = $owned_pets === 'yes' ? (isset($_POST['reason_no_pet']) ? $_POST['reason_no_pet'] : '') : null;

    // Validation
    if (empty($name) || empty($email) || empty($contact) || empty($address)) {
        echo "All required fields are mandatory.";
        exit();
    }

    // Save data to database
    $stmt = $conn->prepare("INSERT INTO buy_form_data (user_id, pet_id, name, email, contact, address, do_you_work, occupation, owned_pets, pet_types, ownership_duration, reason_no_pet) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        exit();
    }

    $stmt->bind_param(
        "iissssssssss",
        $user_id,
        $pet_id,
        $name,
        $email,
        $contact,
        $address,
        $do_you_work,
        $occupation,
        $owned_pets,
        $pet_types,
        $ownership_duration,
        $reason_no_pet
    );

    if ($stmt->execute()) {
        echo "Purchase request submitted successfully.";
    } else {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

</body>

</html>