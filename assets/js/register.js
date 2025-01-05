function sendNIC() {
  const nicNumber = document.getElementById("nicField").value;

  if (validateNIC()) {
    // Only redirect if NIC is valid
    window.location.href = `register.php?nic=${encodeURIComponent(nicNumber)}`;
  }
}

document.getElementById("nicField").addEventListener("input", function () {
  this.value = this.value.toUpperCase();
});

document.getElementById("name").addEventListener("input", function () {
  this.value = this.value.toUpperCase();
});

function validateNIC() {
  const nic = document.getElementById("nicField").value;
  const alertMessage = document.getElementById("alert-message");

  alertMessage.classList.add("d-none");
  alertMessage.textContent = "";

  // Check if NIC is empty
  if (nic === "") {
    alertMessage.textContent = "Please enter an NIC number.";
    alertMessage.classList.remove("d-none");
    return false;
  }

  // Check if NIC has 12 digits and contains only numbers
  if (nic.length === 12 && !isNaN(nic)) {
    alertMessage.textContent = "";
    return true;
  } else if (
    nic.length === 10 &&
    nic.charAt(9) === "V" &&
    !isNaN(nic.substring(0, 9))
  ) {
    alertMessage.textContent = "";
    return true;
  } else {
    alertMessage.textContent = "Invalid NIC number.";
    alertMessage.classList.remove("d-none");
    return false; // Prevent form submission
  }
}
