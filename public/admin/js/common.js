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

    // var $j = jQuery.noConflict();
$(document).ready(function() {
    let table = $('#userTableList').DataTable({
        processing: true,
        serverSide: true,
        ajax: usersListUrl,  // Ensure this URL is correct

        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            //{ data: 'profile_image', name: 'profile_image', orderable: false, searchable: false },
            //{ data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            //{ data: 'phone_number', name: 'phone_number' },
            { data: 'photo_count', name: 'photo_count', orderable: false, searchable: false },
            // { data: 'status', name: 'status', orderable: false, searchable: false },
            // { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    // STATUS TOGGLE
$('#userTableList').on('click', '.toggle-status', function () {
    let id = $(this).data('id');
    let status = $(this).data('status');
    if (confirm("Are you sure?")) {
       $.ajax({
    url: usersUpdateUrl,
    type: "get",
    data: {
        id: id,
        status: status
    },
    success: function (response) {
        console.log("SUCCESS:", response);
        table.ajax.reload(null, false);
    },
    error: function (xhr) {
        console.log("STATUS CODE:", xhr.status);
        console.log("RESPONSE:", xhr.responseText);
        alert("Failed. Check console.");
    }
});
    }
});
    // DELETE USER
    $('#userTableList').on('click', '.delete-user', function () {
        let id = $(this).data('id');
        if (confirm("Are you sure you want to delete this user?")) {
            $.get("{{ route('admin.users.update.data') }}", {
                _token: "{{ csrf_token() }}",
                id: id,
                status: -1
            }, function () {
                table.ajax.reload(null, false);
            });
        }
    });
});

});
