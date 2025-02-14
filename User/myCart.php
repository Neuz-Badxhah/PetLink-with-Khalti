<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection parameters
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

$user_id = $_SESSION['user_id'];

// Handle adding item to cart
if (isset($_POST['pet_id'])) {
    $pet_id = $_POST['pet_id'];

    // Prepare SQL statement to insert into cart
    $sql_insert = "INSERT INTO cart (user_id, pet_id) VALUES (?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ii", $user_id, $pet_id);

    // Execute SQL statement
    if ($stmt_insert->execute()) {
        echo "Pet added to cart successfully.";
        // Optional: Redirect to cart page or refresh the current page
        // header("Location: myCart.php");
        // exit();
    } else {
        echo "Error adding pet to cart: " . $conn->error;
    }
}

// Handle removing item from cart
if (isset($_POST['delete_item'])) {
    $delete_pet_id = $_POST['delete_item'];

    // Prepare SQL statement to delete item from cart
    $sql_delete = "DELETE FROM cart WHERE user_id = ? AND pet_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("ii", $user_id, $delete_pet_id);

    // Execute SQL statement
    if ($stmt_delete->execute()) {
        echo "Item removed from cart successfully.";
        // Optional: Redirect to cart page or refresh the current page
        // header("Location: myCart.php");
        // exit();
    } else {
        echo "Error removing item from cart: " . $conn->error;
    }
}

// Fetch cart items for the current user
$sql_cart = "SELECT petdetails.id, petdetails.pet_image, petdetails.pet_name, petdetails.price 
             FROM cart 
             INNER JOIN petdetails ON cart.pet_id = petdetails.id 
             WHERE cart.user_id = ?";
$stmt_cart = $conn->prepare($sql_cart);
$stmt_cart->bind_param("i", $user_id);
$stmt_cart->execute();
$result_cart = $stmt_cart->get_result();

$cart_items = [];
if ($result_cart->num_rows > 0) {
    while ($row = $result_cart->fetch_assoc()) {
        $cart_items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cart.css">
    <link rel="web icon" href="../Images/logo.jpg">

    <title>My Cart</title>
</head>

<body>

    <div class="cart-container">
        <h2 class="cart-title">My Cart</h2>
        <?php if (!empty($cart_items)) : ?>
        <ul class="cart-list">
            <?php foreach ($cart_items as $item) : ?>
            <li class="cart-item">
                <img src="../purchase/uploads/<?php echo htmlspecialchars($item['pet_image']); ?>" alt="Pet Image"
                    class="cart-item-image">
                <div class="cart-item-details">
                    <p><?php echo htmlspecialchars($item['pet_name']); ?></p>
                    <p>Price: $<?php echo htmlspecialchars($item['price']); ?></p>
                </div>
                <form action="../purchase/buy_form.php" method="post" style="display: inline;">
                    <!-- Replace 'checkout.php' with your checkout page -->
                    <input type="hidden" name="pet_id" value="<?php echo $item['id']; ?>">
                    <button type="submit" class="buy-button">Buy Now</button>
                </form>
                <form action="../purchase/buy.php" method="get" style="display: inline;">
                    <input type="hidden" name="pet_id" value="<?php echo $item['id']; ?>">
                    <button type="submit" class="see-more-button">See More</button>
                </form>
                <form action="" method="post" style="display: inline;">
                    <input type="hidden" name="delete_item" value="<?php echo $item['id']; ?>">
                    <button type="submit" class="delete-button">Delete</button>
                </form>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else : ?>
        <p>No items in cart.</p>
        <?php endif; ?>
    </div>

</body>

</html>

<?php
// Close database connection
$conn->close();
?>