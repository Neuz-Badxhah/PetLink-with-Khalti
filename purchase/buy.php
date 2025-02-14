<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
$user_id = $_SESSION['user_id']; // Get user ID from session

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

// Fetch only approved pets from the database
$sql = "SELECT id, pet_image, pet_video, pet_name, pet_age, pet_size, pet_type, pet_breed, health_status, description, price, upload_date FROM petdetails WHERE status='approved'";
$result = $conn->query($sql);

// Initialize an array to hold pets by type
$petsByType = ["cat" => [], "dog" => [], "other" => []];

// Categorize pets by type
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $petType = htmlspecialchars($row["pet_type"]);
    if (isset($petsByType[$petType])) {
      $petsByType[$petType][] = $row;
    } else {
      $petsByType["other"][] = $row;
    }
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="buy.css">
  <title>Available Pets</title>
</head>

<body>
  <h1>Available Pets</h1>
  <button type="button" class="profile" onclick="window.location.href='../User/Dashboard.php';">Profile</button>


  <!-- Cats Section -->
  <h2>Cats</h2>
  <div class="pet-container">
    <?php
    if (!empty($petsByType['cat'])) {
      foreach ($petsByType['cat'] as $row) {
        echo '<div class="pet-item">
                        <details>
                            <summary>
                                <img src="' . htmlspecialchars($row["pet_image"]) . '" alt="Pet Image">
                            </summary>
                            <div class="pet-contain-container" id="pet-' . $row["id"] . '">
                                <div class="video-section">
                                    <label for="video">Video Clip:</label>
                                    <video src="' . htmlspecialchars($row["pet_video"]) . '" controls autoplay muted></video>
                                </div>
                                <div class="info-section">
                                    <div class="info">
                                        <label for="name">Name:</label>
                                        <p>' . htmlspecialchars($row["pet_name"]) . '</p>
                                    </div>
                                    <div class="info">
                                        <label for="age">Age (in yrs):</label>
                                        <p>' . htmlspecialchars($row["pet_age"]) . '</p>
                                    </div>
                                    <div class="info">
                                        <label for="size">Size:</label>
                                        <p>' . htmlspecialchars($row["pet_size"]) . '</p>
                                    </div>
                                    <div class="info">
                                        <label for="breed">Breed:</label>
                                        <p>' . htmlspecialchars($row["pet_breed"]) . '</p>
                                    </div>
                                    <div class="info">
                                        <label for="health_status">Health Status:</label>
                                        <p>' . htmlspecialchars($row["health_status"]) . '</p>
                                    </div>
                                    <div class="info">
                                        <label for="description">Description:</label>
                                        <p>' . htmlspecialchars($row["description"]) . '</p>
                                    </div>
                                    <div class="info">
                                        <label for="price">Price (Rs.):</label>
                                        <p>' . htmlspecialchars($row["price"]) . '</p>
                                    </div>
                                </div>
                                <div class="button-form">
                                    <button type="button" class="buy-button" data-pet-id="' . $row["id"] . '" data-pet-name="' . htmlspecialchars($row["pet_name"]) . '" data-price="' . htmlspecialchars($row["price"]) . '" onclick="redirectToBuyForm(this)">Buy</button>
                                    <button type="button" class="add-to-cart-button" data-pet-id="' . $row["id"] . '">Add to Cart</button>
                                </div>
                            </div>
                        </details>
                    </div>';
      }
    } else {
      echo '<p>No cats available at the moment.</p>';
    }
    ?>
  </div>

  <!-- Dogs Section -->
  <h2>Dogs</h2>
  <div class="pet-container">
    <?php
    if (!empty($petsByType['dog'])) {
      foreach ($petsByType['dog'] as $row) {
        echo '<div class="pet-item">
                <details>
                  <summary>
                    <img src="' . htmlspecialchars($row["pet_image"]) . '" alt="Pet Image">
                  </summary>
                  <div class="pet-contain-container" id="pet-' . $row["id"] . '">
                    <div class="video-section">
                      <label for="video">Video Clip:</label>
                      <video src="' . htmlspecialchars($row["pet_video"]) . '" controls autoplay muted></video>
                    </div>
                    <div class="info-section">
                      <div class="info">
                        <label for="name">Name:</label>
                        <p>' . htmlspecialchars($row["pet_name"]) . '</p>
                      </div>
                      <div class="info">
                        <label for="age">Age (in yrs):</label>
                        <p>' . htmlspecialchars($row["pet_age"]) . '</p>
                      </div>
                      <div class="info">
                        <label for="size">Size:</label>
                        <p>' . htmlspecialchars($row["pet_size"]) . '</p>
                      </div>
                      <div class="info">
                        <label for="breed">Breed:</label>
                        <p>' . htmlspecialchars($row["pet_breed"]) . '</p>
                      </div>
                      <div class="info">
                        <label for="health_status">Health Status:</label>
                        <p>' . htmlspecialchars($row["health_status"]) . '</p>
                      </div>
                      <div class="info">
                        <label for="description">Description:</label>
                        <p>' . htmlspecialchars($row["description"]) . '</p>
                      </div>
                      <div class="info">
                        <label for="price">Price (Rs.):</label>
                        <p>' . htmlspecialchars($row["price"]) . '</p>
                      </div>
                    </div>
                    <div class="button-form">
                      <button type="button" class="buy-button" data-pet-id="' . $row["id"] . '" data-pet-name="' . htmlspecialchars($row["pet_name"]) . '" data-price="' . htmlspecialchars($row["price"]) . '" onclick="redirectToBuyForm(this)">Buy</button>
                      <button type="button" class="add-to-cart-button" data-pet-id="' . $row["id"] . '">Add to Cart</button>
                    </div>
                  </div>
                </details>
              </div>';
      }
    } else {
      echo '<p>No dogs available at the moment.</p>';
    }
    ?>
  </div>

  <!-- Other Pets Section -->
  <h2>Other Pets</h2>
  <div class="pet-container">
    <?php
    if (!empty($petsByType['other'])) {
      foreach ($petsByType['other'] as $row) {
        echo '<div class="pet-item">
                <details>
                  <summary>
                    <img src="' . htmlspecialchars($row["pet_image"]) . '" alt="Pet Image">
                  </summary>
                  <div class="pet-contain-container" id="pet-' . $row["id"] . '">
                    <div class="video-section">
                      <label for="video">Video Clip:</label>
                      <video src="' . htmlspecialchars($row["pet_video"]) . '" controls autoplay muted></video>
                    </div>
                    <div class="info-section">
                      <div class="info">
                        <label for="name">Name:</label>
                        <p>' . htmlspecialchars($row["pet_name"]) . '</p>
                      </div>
                      <div class="info">
                        <label for="age">Age (in yrs):</label>
                        <p>' . htmlspecialchars($row["pet_age"]) . '</p>
                      </div>
                      <div class="info">
                        <label for="size">Size:</label>
                        <p>' . htmlspecialchars($row["pet_size"]) . '</p>
                      </div>
                      <div class="info">
                        <label for="breed">Breed:</label>
                        <p>' . htmlspecialchars($row["pet_breed"]) . '</p>
                      </div>
                      <div class="info">
                        <label for="health_status">Health Status:</label>
                        <p>' . htmlspecialchars($row["health_status"]) . '</p>
                      </div>
                      <div class="info">
                        <label for="description">Description:</label>
                        <p>' . htmlspecialchars($row["description"]) . '</p>
                      </div>
                      <div class="info">
                        <label for="price">Price (Rs.):</label>
                        <p>' . htmlspecialchars($row["price"]) . '</p>
                      </div>
                    </div>
                    <div class="button-form">
                      <button type="button" class="buy-button" data-pet-id="' . $row["id"] . '" data-pet-name="' . htmlspecialchars($row["pet_name"]) . '" data-price="' . htmlspecialchars($row["price"]) . '" onclick="redirectToBuyForm(this)">Buy</button>
                      <button type="button" class="add-to-cart-button" data-pet-id="' . $row["id"] . '">Add to Cart</button>
                    </div>
                  </div>
                </details>
              </div>';
      }
    } else {
      echo '<p>No other pets available at the moment.</p>';
    }
    ?>
  </div>


  <script>
    function redirectToBuyForm(button) {
      var petId = button.getAttribute('data-pet-id');
      button.textContent = "Buying Process";
      button.disabled = true;
      window.location.href = 'buy_form.php?pet_id=' + petId;
    }
  </script>

  <script src="buy.js"></script>
</body>

</html>