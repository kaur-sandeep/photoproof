document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById('resetForm');

    if (form) {

        form.addEventListener('submit', function (e) {

            let password = document.getElementById('password').value;
            let confirmPassword = document.getElementById('confirmPassword').value;

            let passwordError = document.getElementById('passwordError');
            let confirmPasswordError = document.getElementById('confirmPasswordError');

            // Clear old errors
            passwordError.innerText = '';
            confirmPasswordError.innerText = '';

            let isValid = true;

            if (password === '') {
                passwordError.innerText = 'Password is required.';
                isValid = false;
            } else if (password.length < 6) {
                passwordError.innerText = 'Password must be at least 6 characters.';
                isValid = false;
            }

            if (confirmPassword === '') {
                confirmPasswordError.innerText = 'Confirm password is required.';
                isValid = false;
            } else if (password !== confirmPassword) {
                confirmPasswordError.innerText = 'Passwords do not match.';
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }

        });

    }

});


document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".map-preview").forEach(function (map) {

        map.addEventListener("click", function () {
           
            let lat = this.getAttribute("data-lat");
            let lng = this.getAttribute("data-lng");

            let mapUrl = `https://maps.google.com/maps?q=${lat},${lng}&z=15&output=embed`;

          
            document.getElementById("commonheader").innerText = "Location Map";

        
            document.getElementById("commonModalBody").innerHTML =
                `<iframe width="100%" height="400" style="border:0;" 
                 src="${mapUrl}" allowfullscreen></iframe>`;

            var modal = new bootstrap.Modal(document.getElementById('commonModal'));
            modal.show();
        });

    });

});