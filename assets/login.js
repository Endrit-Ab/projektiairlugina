function validateForm() {
    const emailFieldset = document.querySelector('input[type="email"]').closest('.fieldset');
    const passwordFieldset = document.querySelector('input[type="password"]').closest('.fieldset');

    const emailField = document.querySelector('input[type="email"]');
    const passwordField = document.querySelector('input[type="password"]');

    const email = emailField.value.trim();
    const password = passwordField.value.trim();

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

   
   
