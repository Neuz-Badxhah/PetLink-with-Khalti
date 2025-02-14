<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs (example)
    $name = htmlspecialchars($_POST['name']);
    $user_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Replace with your admin's email address
    $admin_email = "neuzbadxhah@gmail.com";

    // Email headers
    $headers = "From: $name <$user_email>\r\n";
    $headers .= "Reply-To: $user_email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Email body
    $email_body = "You have received a new message from the contact form on your website.\n\n" .
        "Name: $name\n" .
        "Email: $user_email\n" .
        "Subject: $subject\n" .
        "Message:\n$message";

    // Send email using mail() function
    if (mail($admin_email, $subject, $email_body, $headers)) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false));
    }
}
