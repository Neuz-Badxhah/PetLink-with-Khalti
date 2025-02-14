document.addEventListener("DOMContentLoaded", function () {
  const addToCartButtons = document.querySelectorAll(".add-to-cart-button");

  addToCartButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const petId = this.getAttribute("data-pet-id");

      fetch("add_to_cart.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `pet_id=${petId}`,
      })
        .then((response) => response.text())
        .then((data) => alert(data))
        .catch((error) => console.error("Error:", error));
    });
  });
});

function redirectToBuyForm(button) {
  var petId = button.getAttribute("data-pet-id");
  var petName = button.getAttribute("data-pet-name");
  var price = button.getAttribute("data-price");

  // Construct the URL with query parameters
  var url =
    "buy_form.php?pet_id=" +
    encodeURIComponent(petId) +
    "&pet_name=" +
    encodeURIComponent(petName) +
    "&price=" +
    encodeURIComponent(price);

  // Redirect to the buy_form.php with the query parameters
  window.location.href = url;
}
