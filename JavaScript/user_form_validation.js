function validateForm() {
  const name = document.getElementById("name").value.trim();
  const address = document.getElementById("address").value.trim();
  const contact = document.getElementById("contact").value.trim();
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value.trim();

  if (!name || !address || !contact || !email || !password) {
    alert("All fields are required.");
    return false;
  }

  const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  if (!emailPattern.test(email)) {
    alert("Invalid email address.");
    return false;
  }

  const contactPattern = /^[0-9]{10}$/;
  if (!contactPattern.test(contact)) {
    alert("Invalid contact number. It should be a 10 digit number.");
    return false;
  }
}
