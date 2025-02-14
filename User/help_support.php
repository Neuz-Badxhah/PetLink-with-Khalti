<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="web icon" href="../Images/logo.jpg">
    <title>Contact Support</title>
    <style>
    /* General styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    /* Footer styles */
    footer {
        background-color: #007bff;
        color: #fff;
        padding: 20px;
        text-align: center;
    }

    .footer-content {
        border-radius: 10px;
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
    }

    .footer-section {
        background-color: #fff;
        color: #333;
        border-radius: 8px;
        padding: 20px;
        margin: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        flex: 1;
        max-width: 300px;
    }

    .footer-section h4 {
        margin-top: 0;
        color: #007bff;
    }

    .contact-details p,
    .quick-link a {
        margin: 5px 0;
    }

    .contact-details a,
    .quick-link a {
        text-decoration: none;
        color: #007bff;
    }

    .contact-details a:hover,
    .quick-link a:hover {
        text-decoration: underline;
    }

    .quick-link a {
        display: flex;
    }



    .social-media a {
        margin: 5px;
    }

    .social-media img {
        width: 30px;
        height: 30px;
    }

    .social-media img:hover {
        transform: scale(1.4);
        transition: 0t.3s;
    }

    .email-support {
        width: 100%;
        max-width: 600px;
        margin: 20px auto;
    }

    details {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    summary {
        cursor: pointer;
        font-size: 1.2rem;
        font-weight: bold;
    }

    summary h2 {
        margin: 0;
    }

    form {
        margin-top: 10px;
    }

    label {
        font-weight: bold;
        display: block;
        margin-top: 10px;
    }

    input[type="text"],
    input[type="email"],
    textarea {
        width: calc(100% - 20px);
        padding: 10px;
        margin-top: 5px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
        box-sizing: border-box;
        resize: vertical;
    }

    button[type="submit"] {
        width: 100%;
        padding: 10px;
        background-color: #28a745;
        color: #fff;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 16px;
    }

    button[type="submit"]:hover {
        background-color: #218838;
    }
    </style>
</head>

<body>
    <footer>
        <h3>Connect with Us</h3>
        <div class="footer-content">
            <div class="footer-section contact-details">
                <h4>Contact</h4>
                <p>Email: <a href="mailto:neuzbadxhah@gmail.com">admin@gmail.com</a></p>
                <p>Phone: <a href="tel:+1234567890">+977 98********</a></p>
                <p>Address: 123 xyz ABC</p>
            </div>
            <div class="footer-section social-media">
                <h4>Social Media</h4>
                <a href="https://www.facebook.com"><img src="../Images/Facebook.png" alt="Facebook Logo"></a>
                <a href="https://www.instagram.com"><img src="../Images/Instagram.png" alt="Instagram Logo"></a>
                <a href="https://www.twitter.com"><img src="../Images/Twitter.png" alt="Twitter Logo"></a>
                <a href="https://www.youtube.com"><img src="../Images/Youtube.png" alt="YouTube Logo"></a>
            </div>
            <div class="footer-section quick-link">
                <h4>Quick Links</h4>
                <a href="../index.php">Home</a>
                <a href="../Login Register/login.php">Pet</a>
                <a href="../Login Register/login.php">Become a Seller</a>
                <a href="../about.html">About</a>
                <a href="../Login Register/login.php">Login</a>
            </div>
        </div>
    </footer>
    <div class="email-support">
        <details>
            <summary>
                <h2>Direct Email Here</h2>
            </summary>
            <form id="contactForm">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required>

                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="4" required></textarea>

                <button type="submit">Send Message</button>
            </form>
        </details>
    </div>

    <script>
    document.getElementById('contactForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form submission

        // Collect form data
        var formData = new FormData(this);

        // Send form data to server-side script using fetch API
        fetch('send_email.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Assuming server responds with JSON
            .then(data => {
                if (data.success) {
                    alert('Your message has been sent. We will get back to you soon.');
                    // Optionally clear form inputs
                    document.getElementById('contactForm').reset();
                } else {
                    alert(
                        'Failed to send your message. Please try again later. if you failed to sent mail than you can  direct mail in above mail '
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(
                    'Failed to send your message. Please try again later. if you failed to sent mail than you can  direct mail in above mail'
                );
            });
    });
    </script>
</body>

</html>