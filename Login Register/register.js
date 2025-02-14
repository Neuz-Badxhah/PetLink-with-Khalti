document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");

  form.addEventListener("submit", function (event) {
    const username = document.getElementById("username").value.trim();
    const address = document.getElementById("address").value.trim();
    const contact = document.getElementById("contact").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();
    const confirmPassword = document
      .getElementById("confirm-password")
      .value.trim();

    // Basic validation checks
    if (!username) {
      alert("Username is required.");
      event.preventDefault();
      return;
    }

    if (!address) {
      alert("Address is required.");
      event.preventDefault();
      return;
    }

    if (!contact) {
      alert("Contact is required.");
      event.preventDefault();
    }
    // Contact number validation
    const contactPattern = /^[0-9]+$/;
    if (!contactPattern.test(contact)) {
      alert("Contact must be a number.");
      event.preventDefault();
      return;
    }
    if (!email) {
      alert("Email is required.");
      event.preventDefault();
      return;
    }

    // Email format validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      alert("Invalid email format.");
      event.preventDefault();
      return;
    }

    if (!password) {
      alert("Password is required.");
      event.preventDefault();
      return;
    }

    if (!confirmPassword) {
      alert("Please confirm your password.");
      event.preventDefault();
      return;
    }

    if (password !== confirmPassword) {
      alert("Passwords do not match.");
      event.preventDefault();
      return;
    }

    // Password strength validation
    if (password.length < 8) {
      alert("Password must be at least 8 characters long.");
      event.preventDefault();
      return;
    }
    // Confirmation alert
    const isConfirmed = confirm("Are you ready for submission?");
    if (!isConfirmed) {
      event.preventDefault();
    }
  });
});
