document.getElementById('resetForm').addEventListener('submit', function(e) {

    let password = document.getElementById('password').value;
    let confirmPassword = document.getElementById('confirmPassword').value;

    let passwordError = document.getElementById('passwordError');
    let confirmPasswordError = document.getElementById('confirmPasswordError');

    // Clear old errors
    passwordError.innerText = '';
    confirmPasswordError.innerText = '';

    let isValid = true;

    // Password required
    if (password === '') {
        passwordError.innerText = 'Password is required.';
        isValid = false;
    }

    // Minimum length
    else if (password.length < 6) {
        passwordError.innerText = 'Password must be at least 6 characters.';
        isValid = false;
    }

    // Confirm password required
    if (confirmPassword === '') {
        confirmPasswordError.innerText = 'Confirm password is required.';
        isValid = false;
    }

    // Password match check
    else if (password !== confirmPassword) {
        confirmPasswordError.innerText = 'Passwords do not match.';
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault(); // Stop form submission
    }
});