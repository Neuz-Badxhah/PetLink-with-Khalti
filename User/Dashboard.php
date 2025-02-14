<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login Register/login.php");
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

$user_id = ($_SESSION['user_id']) ? ($_SESSION['user_id']) : "";

// Fetch user details
$sql_user = "SELECT id, username, address, contact, email, profile_picture FROM users WHERE id=?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
} else {
    echo "No user found";
    exit();
}

// Fetch pet listings for the current user
$sql_pet = "SELECT * FROM petdetails WHERE user_id=?";
$stmt_pet = $conn->prepare($sql_pet);
$stmt_pet->bind_param("i", $user_id);
$stmt_pet->execute();
$result_pet = $stmt_pet->get_result();

$pet_listings = [];
if ($result_pet->num_rows > 0) {
    while ($row = $result_pet->fetch_assoc()) {
        $pet_listings[] = $row;
    }
} else {
    $pet_listings = null;
}

$user_id = $_SESSION['user_id'];

// Fetch approved purchase requests for the current user
// Fetch purchase listings
$sql_purchase = "
SELECT b.*, u.email AS buyer_email, u.username AS buyer_name, p.user_id AS owner_id, p.pet_name, p.pet_image, 
p.pet_age, p.pet_size, p.pet_type, p.pet_breed, p.health_status, p.description, p.price 
FROM buy_form_data b
JOIN users u ON b.user_id = u.id
JOIN petdetails p ON b.pet_id = p.id
WHERE b.status = 'approved' AND p.user_id = ?";
$stmt_purchase = $conn->prepare($sql_purchase);
$stmt_purchase->bind_param("i", $user_id);
$stmt_purchase->execute();
$result_purchase = $stmt_purchase->get_result();
$purchase_listings = $result_purchase->num_rows > 0 ? $result_purchase->fetch_all(MYSQLI_ASSOC) : [];


$stmt_purchase = $conn->prepare($sql_purchase);
$stmt_purchase->bind_param("i", $user_id);
$stmt_purchase->execute();
$result_purchase = $stmt_purchase->get_result();

$purchase_listings = [];
if ($result_purchase->num_rows > 0) {
    while ($row = $result_purchase->fetch_assoc()) {
        $purchase_listings[] = $row;
    }
} else {
    $purchase_listings = null;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Css/Dashboard.css">
    <link rel="web icon" href="../Images/logo.jpg">

    <title>Pet Link User</title>
</head>

<body>
    <nav class="navbar">
        <div class="navheading">
            <a href="#" aria-current="page">Pet Link</a>
        </div>
        <div class="navbaritem">
            <ul class="navitem">
                <li><a href="#">Home</a></li>
                <li><a href="../purchase/buy.php">Buy pet</a></li>
                <li><a href="../purchase/Sell_trial.php">Sell Your pet</a></li>
                <li><a href="help_support.php">Help & Support</a></li>
                <li><a href="myCart.php">My Cart</a></li>
                <li class="dropdown">
                    <a href="#" class="profile">
                        <div class="profile-picture">
                            <?php if (!empty($user['profile_picture'])) : ?>
                            <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>"
                                alt="Profile Picture">
                            <?php else : ?>
                            <img src="../Images/Default profile.jpg" alt="Default Profile Picture">
                            <?php endif; ?>
                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="edit_profile.php">Edit Profile</a></li>
                        <li><a href="../Login Register/logout.php">Logout</a></li>

                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <div id="user-profile">
        <div class="profile-container">
            <h2>Your Profile</h2>
            <div class="profile-details">
                <div class="profile-details">
                    <div class="profile-picture-container">
                        <?php if (!empty($user['profile_picture'])) : ?>
                        <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>"
                            alt="Profile Picture">
                        <?php else : ?>
                        <img src="../Images/Default profile.jpg" alt="Default Profile Picture">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="profile-info">
                    <div class="profile-field">
                        <span class="field-label">Username:</span>
                        <span class="field-value"><?php echo htmlspecialchars($user['username']); ?></span>
                    </div>
                    <div class="profile-field">
                        <span class="field-label">Address:</span>
                        <span class="field-value"><?php echo htmlspecialchars($user['address']); ?></span>
                    </div>
                    <div class="profile-field">
                        <span class="field-label">Contact:</span>
                        <span class="field-value"><?php echo htmlspecialchars($user['contact']); ?></span>
                    </div>
                    <div class="profile-field">
                        <span class="field-label">Email:</span>
                        <span class="field-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                </div>
                <div class="profile-actions">
                    <a href="edit_profile.php">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>

    <div id="pet-listings">
        <div class="pet-container">
            <h2>Your Pet Listings</h2>
            <?php if (!empty($pet_listings)) : ?>
            <div class="pet-list">
                <?php foreach ($pet_listings as $pet) : ?>
                <div class="pet-item">
                    <img src="purchase/uploads/<?php echo htmlspecialchars($pet['pet_image']); ?>" alt="Pet Image">
                    <div class="pet-details">
                        <h3><?php echo htmlspecialchars($pet['pet_name']); ?></h3>
                        <p><strong>Age:</strong> <?php echo htmlspecialchars($pet['pet_age']); ?> years</p>
                        <p><strong>Size:</strong> <?php echo htmlspecialchars($pet['pet_size']); ?></p>
                        <p><strong>Type:</strong> <?php echo htmlspecialchars($pet['pet_type']); ?></p>
                        <p><strong>Breed:</strong> <?php echo htmlspecialchars($pet['pet_breed']); ?></p>
                        <p><strong>Health Status:</strong> <?php echo htmlspecialchars($pet['health_status']); ?></p>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($pet['description']); ?></p>
                        <p><strong>Price:</strong> <?php echo htmlspecialchars($pet['price']); ?></p>
                        <p><strong>Payment:</strong> <?php echo htmlspecialchars($pet['status']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($pet['status']); ?></p>
                        
                        <?php if ($pet['status'] == 'pending') : ?>
                        <button class="status-pending">Pending</button>
                        <?php elseif ($pet['status'] == 'approved') : ?>
                        <button class="status-approved">Approved</button>
                        <?php elseif ($pet['status'] == 'rejected') : ?>
                        <button class="status-rejected">Rejected</button>
                        <?php endif; ?>
                        <form action="delete_pet.php" method="post" onsubmit="return confirmDelete()">
                            <input type="hidden" name="pet_id" value="<?php echo $pet['id']; ?>">
                            <button type="submit" class="delete-button">Delete</button>
                        </form>
                        <script>
                        function confirmDelete() {
                            return confirm("Are you sure you want to delete this pet listing?");
                        }
                        </script>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else : ?>
            <p>No pet listings found.</p>
            <?php endif; ?>
        </div>
    </div>
    <div id="purchase-listings">
    <div id="purchase-listings">
    <div class="purchase-container">
        <h2>Your Purchase Listings</h2>
        <?php if (!empty($purchase_listings)) : ?>
        <div class="purchase-list">
            <?php foreach ($purchase_listings as $purchase) : ?>
            <div class="purchase-item">
                <img src="../purchase/uploads/<?php echo htmlspecialchars($purchase['pet_image']); ?>" alt="Pet Image">
                <div class="purchase-details">
                    <h3><?php echo htmlspecialchars($purchase['pet_name']); ?></h3>
                    <p><strong>Age:</strong> <?php echo htmlspecialchars($purchase['pet_age']); ?> years</p>
                    <p><strong>Size:</strong> <?php echo htmlspecialchars($purchase['pet_size']); ?></p>
                    <p><strong>Type:</strong> <?php echo htmlspecialchars($purchase['pet_type']); ?></p>
                    <p><strong>Breed:</strong> <?php echo htmlspecialchars($purchase['pet_breed']); ?></p>
                    <p><strong>Health Status:</strong> <?php echo htmlspecialchars($purchase['health_status']); ?></p>
                    <p><strong>Price:</strong> <?php echo htmlspecialchars($purchase['price']); ?></p>
                    
                    <div class="purchase-status">
                        <p><strong>Status:</strong> 
                            <?php 
                                if ($purchase['status'] == 'approved') {
                                    echo '<span class="status-approved">Approved</span>';
                                } elseif ($purchase['status'] == 'pending') {
                                    echo '<span class="status-pending">Pending Verification</span>';
                                } else {
                                    echo '<span class="status-rejected">Rejected</span>';
                                }
                            ?>
                        </p>

                        <?php if ($purchase['status'] == 'approved') : ?>
                        <p><strong>Verification Status:</strong> <span class="verification-success">Verified</span></p>
                        <?php elseif ($purchase['status'] == 'pending') : ?>
                        <p><strong>Verification Status:</strong> <span class="verification-pending">Pending</span></p>
                        <?php else : ?>
                        <p><strong>Verification Status:</strong> <span class="verification-failed">Failed</span></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
        <p>No purchase listings found.</p>
        <?php endif; ?>
    </div>
</div>

</div>


</body>

</html>