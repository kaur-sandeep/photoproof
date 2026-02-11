$(document).ready(function () {

    if ($('#adminLoginForm').length) {
     
        $('#adminLoginForm').on('submit', function (e) {
            let email = $('#email').val().trim();
            let password = $('#password').val().trim();
            let valid = true;

            $('#emailError').text('');
            $('#passwordError').text('');

            if (email === '') {
                $('#emailError').text('Email is required.');
                valid = false;
            }
            if (password === '') {
                $('#passwordError').text('Password is required.');
                valid = false;
            }
            if (!valid) {
                e.preventDefault();
            }

        });

    }

});
