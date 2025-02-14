<?php
// Database connection
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

// Query to get the latest pets
$sql = "SELECT * FROM petdetails ORDER BY id DESC LIMIT 3";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Css/navbar.css">
    <link rel="stylesheet" href="Css/style.css">
    <link rel="stylesheet" href="Css/footer.css">
    <link rel="web icon" href="Images/logo.jpg">
    <!-- From CDN -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <title>Pet Link</title>
</head>

<body class="p-3 m-0 border-0 bd-example m-0 border-0">
    <nav class="navbar">
        <div class="navheading"><a href="#" aria-current="page">Pet Link</a></div>
        <div class="navbaritem">
            <ul class="navitem">
                <a href="#">
                    <li>Home</li>
                </a>
                <a href="Login Register/login.php">
                    <li>Pet</li>
                </a>
                <a href="Login Register/login.php">
                    <li>Become a Seller</li>
                </a>
                <a href="User/about.html">
                    <li>About</li>
                </a>
                <a href="User/help_support.php">
                    <li>Help & Support</li>
                </a>
                <a href="Login Register/login.php" class="login">Login</a>
            </ul>
        </div>
    </nav>
    <div id="home">
        <!-- Image Wrap / Carousel image -->
        <div id="carouselExample" class="carousel slide">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="Images/dog1.jpg" class="d-block w-100" alt="Image 1" width="300px" height="800px">
                </div>
                <div class="carousel-item">
                    <img src="Images/cat2.jpeg" class="d-block w-100" alt="Image 2" width="300px" height="800px">
                </div>
                <div class="carousel-item">
                    <img src="Images/rabbit2.jpeg" class="d-block w-100" alt="Image 3" width="300px" height="800px">
                </div>
                <div class="carousel-item">
                    <img src="Images/bird1.jpeg" class="d-block w-100" alt="Image 4" width="300px" height="800px">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <img src="Images/left aero.png" alt="Previous" style="height: 30px;">
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <img src="Images/Right aero.png" alt="Next" style="height: 30px;">
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <!-- Welcome Section -->
        <div class="welcome">
            <div class="welcome-content">
                <h1>Welcome to Pet Link!</h1>
                <h3>Pet Link is the online pet shop Nepal.</h3>
                <p>We're dedicated to connecting pet lovers and helping you find the perfect companion. Join our
                    community and experience the joy of responsible pet ownership.</p>
            </div>
        </div>

        <!-- Feature categories -->
        <div data-aos="fade-up" class="featured-categories">
            <div class="category-card">
                <img src="Images/cat2.jpeg" alt="Cats Category" width="400px" height="200px">
                <h2>Cats</h2>
                <p>Explore our collection of adorable cats. Find your purr-fect companion.</p>
                <button class="pet-btn"><a href="Login Register/login.php">View Cat</a></button>
            </div>

            <div class="category-card">
                <img src="Images/dog2.jpeg" alt="Dogs Category" width="400px" height="200px">
                <h2>Dogs</h2>
                <p>Discover various breeds of dogs. From playful puppies to loyal companions.</p>
                <button class="pet-btn"><a href="Login Register/login.php">View Dog</a></button>
            </div>

            <div class="category-card">
                <div class="slider">
                    <img class="card-image" src="Images/rabbit2.jpeg" alt="First image Category" width="400px"
                        height="200px">
                    <img class="card-image" src="Images/bird2.jpeg" alt="Second image Category" width="400px"
                        height="200px">
                </div>
                <script>
                window.onload = function() {
                    var images = document.querySelectorAll('.slider .card-image');
                    var currentIndex = 0;

                    function showNextImage() {
                        for (var i = 0; i < images.length; i++) {
                            images[i].style.display = 'none';
                        }

                        images[currentIndex].style.display = 'block';
                        currentIndex++;

                        if (currentIndex >= images.length) {
                            currentIndex = 0;
                        }
                    }

                    showNextImage();
                    setInterval(showNextImage, 3000);
                };
                </script>
                <h2>Birds and other</h2>
                <p>Colorful and chirpy birds and other pet animal that will brighten up your home. Explore our avian
                    friends.</p>
                <button class="pet-btn"><a href="Login Register/login.php">View Other Pet</a></button>
            </div>
        </div>

        <!-- Latest Pets -->
        <div class="latest">
            <h3>Latest Pet</h3>
            <div data-aos="fade-up" class="latest_pet">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="pet-details">
                    <img src="purchase/uploads/' . htmlspecialchars($row["pet_image"]) . '" alt="Pet Image" width="300px"></br>
                    <p><strong>Name:</strong> ' . htmlspecialchars($row["pet_name"]) . '</p>
                    <p><strong>Age:</strong> ' . htmlspecialchars($row["pet_age"]) . '</p>
                    <p><strong>Size:</strong> ' . htmlspecialchars($row["pet_size"]) . '</p>
                    <p><strong>Breed:</strong> ' . htmlspecialchars($row["pet_breed"]) . '</p>
                    <p><strong>Health Status:</strong> ' . htmlspecialchars($row["health_status"]) . '</p>
                    <p><strong>Description:</strong> ' . htmlspecialchars($row["description"]) . '</p>
                    <div class="form-buttons">
                        <button type="button" onclick="location.href=\'Login Register/login.php\'">Buy</button>
                        <button type="button" onclick="location.href=\'Login Register/login.php\'">Add to Cart</button>
                   </div>
                </div>';
                    }
                } else {
                    echo "<p>No pets available at the moment.</p>";
                }
                ?>
            </div>
        </div>


        <!-- How It Works -->
        <section class="how-it-works">
            <h2>How It Works</h2>
            <div data-aos="fade-up" class="steps">
                <div class="step">
                    <a href="Images/first.png">
                        <img src="Images/first.png" alt="Step 1" width="200px" height="150px">

                    </a>
                    <p>Login or create your account.</p>
                </div>
                <div class="step">
                    <a href="Images/second.png">

                        <img src="Images/second.png" alt="Step 2 " width="200px" height="150px">

                    </a>
                    <p>User profile where incude user activities like selling pet and buying new member</p>
                </div>
                <div class="step">
                    <a href="Images/third.png">

                        <img src="Images/third.png" alt="Step 3" width="200px" height="150px">

                    </a>
                    <p>Sell your pet through this site.</p>
                </div>
                <div class="step">
                    <a href="Images/fourth.png">
                        <img src="Images/fourth.png" alt="Step 4" width="200px" height="150px">

                    </a>
                    <p>Meet the pet, and ensure it's a good fit for your home. </p>
                </div>
                <div class="step">
                    <a href="Images/fifth.png">

                        <img src="Images/fifth.png" alt="purchase Pet" width="200px" height="150px">

                    </a>
                    <p>Complete the transaction securely through Pet Link's platform pr add in cart for later.</p>
                </div>
            </div>
        </section>

        <!-- Pet Care Tips -->
        <section class="pet-care-tips">
            <h2>Pet Care Tips</h2>
            <div data-aos="fade-up" class="tips">
                <section class="care-tips">
                    <h3>Care Tips</h3>
                    <ul>
                        <li>Provide a balanced and nutritious diet for your pet.</li>
                        <li>Regularly groom your pet to keep their coat healthy and clean.</li>
                        <li>Ensure your pet has access to fresh water at all times.</li>
                        <li>Create a comfortable and safe living environment for your pet.</li>
                    </ul>
                    <div class="img"><img src="Images/cat2.jpeg" alt="pet Image"></div>
                </section>

                <section class="training-tips">
                    <h3>Training Tips</h3>
                    <ul>
                        <li>Use positive reinforcement to encourage good behavior.</li>
                        <li>Be consistent with commands and rewards during training sessions.</li>
                        <li>Patience is key – training takes time and repetition.</li>
                        <li>Seek professional help if you encounter challenges in training.</li>
                    </ul>
                    <div class="img"><img src="Images/dog3.jpeg" alt="pet Image"></div>
                </section>

                <section class="health-tips">
                    <h3>Health Tips</h3>
                    <ul>
                        <li>Schedule regular veterinary check-ups for vaccinations and health assessments.</li>
                        <li>Monitor your pet's weight and adjust their diet as needed.</li>
                        <li>Be aware of any changes in behavior, appetite, or activity levels.</li>
                        <li>Keep your pet's living area clean to prevent health issues.</li>
                    </ul>
                    <div class="img"><img src="Images/rabbit2.jpeg" alt="pet Image"></div>
                </section>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="testimonials">
            <h2>What Our Users Say</h2>
            <div data-aos="fade-up" class="testimonial-container">
                <blockquote>
                    <p>"I recently adopted a lovely cat through Pet Link. The process was smooth, and the team was very
                        helpful in answering all my questions. My new furry friend brings so much joy to my life!"</p>
                    <cite>- HappyPetOwner123</cite>
                </blockquote>
                <blockquote>
                    <p>"I highly recommend Pet Link for anyone looking to find a pet. The platform is user-friendly, and
                        I found my perfect dog within a week. The adoption experience was fantastic!"</p>
                    <cite>- DogLover456</cite>
                </blockquote>
                <blockquote>
                    <p>"The support from Pet Link's customer service was outstanding. They guided me through the
                        adoption process, and I now have a beautiful parrot as a new member of my family. Thank you, Pet
                        Link!"</p>
                    <cite>- BirdWatcher789</cite>
                </blockquote>
            </div>
        </section>

        <!-- News and Updates -->
        <section class="news-updates">
            <h2>News and Updates</h2>
            <div data-aos="fade-up" class="blog-posts">
                <div class="blog-post">
                    <h3>Exciting Platform Update!</h3>
                    <p>Learn about our latest features and improvements to enhance your experience on Pet Link.</p>
                    <img data-aos="fade-up" src="Images/dog3.jpeg" alt="Image1" class="img">
                </div>
                <div class="blog-post">
                    <h3>Meet Our New Team Members</h3>
                    <p>Discover the faces behind Pet Link and our shared passion for connecting pet lovers.</p>
                    <img data-aos="fade-up" src="Images/cat3.jpeg" alt="Image1" class="img">
                </div>
                <div class="blog-post">
                    <h3>Upcoming Events</h3>
                    <p>Stay tuned for exciting pet-related events happening in your area and join the fun!</p>
                    <img data-aos="fade-up" src="Images/bird2.jpeg" alt="Image1" class="img">
                </div>
                <div class="blog-post">
                    <h3>Important Pet Care Tips</h3>
                    <p>Check out our latest blog post for essential tips on caring for your furry friends.</p>
                    <img data-aos="fade-up" src="Images/cat3.jpeg" alt="Image1" class="img">
                </div>
                <div class="blog-post">
                    <h3>Adoption Success Stories</h3>
                    <p>Read heartwarming stories about pets finding their forever homes through Pet Link.</p>
                    <img data-aos="fade-up" src="Images/dog4.jpeg" alt="Image1" class="img">
                </div>
            </div>
        </section>
    </div>
    <footer>
        <h3>Connect with Us</h3>
        <div class="footer-content">
            <div class=" footer-section contact-details">
                <div class="footer-header">Contact</div>
                <p>Email: <a href="mailto:info@example.com">info@example.com</a></p>
                <p>Phone: <a href="tel:+1234567890">+977 98********</a></p>
                <p>Address: 123 xyz ABC</p>
            </div>
            <div class=" footer-section social-media">
                <div class="footer-class">Social Media</div>
                <a href="www.facebook.com"><img src="Images/Facebook.png" alt="Facebook Logo"></a>
                <a href="www.instagram.com"><img src="Images/Instagram.png" alt="Instagram Logo"></a>
                <a href="www.twitter.com"><img src="Images/Twitter.png" alt="Twitter Logo"></a>
                <a href="www.youtube.com"><img src="Images/Youtube.png" alt="YouTube Logo"></a>
            </div>
            <div class=" footer-section quick-link">
                <div class="footer-header">Quick link</div>
                <a href="Login Register/login.php">Pet</a>
                <a href="Login Register/login.php">Become a seller</a>
                <a href="about.html">About</a>
                <a href="help_support.html">Help & Support</a>
                <a href="Login Register/login.php">Login</a>
            </div>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init();
    });
    </script>
</body>

</html>
<?php
// Close the database connection
$conn->close();
?>