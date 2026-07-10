const form = document.getElementById("signupForm");
const formMessage = document.getElementById("formMessage");

const nameInput = document.getElementById("name");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById("password");

function setError(input, message) {
  const formGroup = input.parentElement;
  const errorElement = formGroup.querySelector(".error");
  errorElement.textContent = message;
  input.classList.remove("valid");
  input.classList.add("invalid");
}

function setSuccess(input) {
  const formGroup = input.parentElement;
  const errorElement = formGroup.querySelector(".error");
  errorElement.textContent = "";
  input.classList.remove("invalid");
  input.classList.add("valid");
}

function validateName() {
  const nameValue = nameInput.value.trim();
  if (nameValue === "") {
    setError(nameInput, "Name is required.");
    return false;
  } else if (nameValue.length < 3) {
    setError(nameInput, "Name must be at least 3 characters.");
    return false;
  } else {
    setSuccess(nameInput);
    return true;
  }
}

function validateEmail() {
  const emailValue = emailInput.value.trim();
  const emailPattern = /^[^s@]+@[^s@]+.[^s@]+$/;

  if (emailValue === "") {
    setError(emailInput, "Email is required.");
    return false;
  } else if (!emailPattern.test(emailValue)) {
    setError(emailInput, "Enter a valid email address.");
    return false;
  } else {
    setSuccess(emailInput);
    return true;
  }
}

function validatePassword() {
  const passwordValue = passwordInput.value.trim();

  if (passwordValue === "") {
    setError(passwordInput, "Password is required.");
    return false;
  } else if (passwordValue.length < 8) {
    setError(passwordInput, "Password must be at least 8 characters.");
    return false;
  } else {
    setSuccess(passwordInput);
    return true;
  }
}

nameInput.addEventListener("input", validateName);
emailInput.addEventListener("input", validateEmail);
passwordInput.addEventListener("input", validatePassword);

form.addEventListener("submit", function (e) {
  e.preventDefault();

  const isNameValid = validateName();
  const isEmailValid = validateEmail();
  const isPasswordValid = validatePassword();

  if (isNameValid && isEmailValid && isPasswordValid) {
    formMessage.style.color = "#22c55e";
    formMessage.textContent = "Form submitted successfully!";
    form.reset();

    document.querySelectorAll("input").forEach((input) => {
      input.classList.remove("valid");
    });
  } else {
    formMessage.style.color = "#fca5a5";
    formMessage.textContent = "Please fix the errors above.";
  }
});
