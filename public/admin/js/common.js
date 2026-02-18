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
        ajax: window.APP_URL + '/admin/users/list/two',  // Ensure this URL is correct

        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'profile_image', name: 'profile_image', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            //{ data: 'phone_number', name: 'phone_number' },
            { data: 'photo_count', name: 'photo_count', orderable: false, searchable: false },
            { data: 'device', name: 'device'},
            { data: 'created_at', name: 'created_at'},
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
    url: window.APP_URL + 'users/update/data',
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
            $.get(window.APP_URL + '/admin/users/update/data', {
                _token: "{{ csrf_token() }}",
                id: id,
                status: -1
            }, function () {
                table.ajax.reload(null, false);
            });
        }
    });
});


$(document).ready(function() {
    let table = $('#photoTableList').DataTable({
        processing: true,
        serverSide: true,
        ajax: window.APP_URL + '/admin/photos/list',  // Ensure this URL is correct

        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'photo', name: 'photo', orderable: false, searchable: false },
            { data: 'random_id', name: 'random_id' },
            { data: 'name', name: 'name' },
            { data: 'location', name: 'location' },
            { data: 'user_name', name: 'user_name' },
            { data: 'view_count', name: 'view_count', orderable: false, searchable: false },
            { data:'status', name:'status'},
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
            
        ]
    });

        // STATUS TOGGLE
$('#photoTableList').on('click', '.toggle-state', function () {
    let id = $(this).data('id');
    let state = $(this).data('state');
    if (confirm("Are you sure?")) {
       $.ajax({
    url: window.APP_URL + '/admin/photos/update/data',
    type: "get",
    data: {
        id: id,
        state: state
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
    $('#photoTableList').on('click', '.delete-user', function () {
        let id = $(this).data('id');
        if (confirm("Are you sure you want to delete this user?")) {
            $.get(window.APP_URL + '/admin/photos/update/data', {
                id: id,
                state: -1
            }, function () {
                table.ajax.reload(null, false);
            });
        }
    });

});
    
$(document).ready(function() {
    let table = $('#photodataTableList').DataTable({
        processing: true,
        serverSide: true,
        ajax: window.APP_URL + '/admin/fetch/users/images/' +  window.USER_ID,
        columns: [
            { data: 'serial_number', name: 'serial_number' },
            // { data: 'email', name: 'email' },
            {
                data: 'images',
                name: 'images',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    if (data) {
                        return data;
                    } else {
                        return '<span>No images available</span>';
                    }
                }
            },
            {data:'view_count', name:'view_count'},
            {
                data: 'upload_track_details',
                name: 'upload_track_details',
                // orderable: false,
                // searchable: false,
                render: function(data, type, row) {
                    if (data) {
                        return data;
                    } else {
                        return '<span>No upload data available</span>';
                    }
                }
            }
            // ,
            // {
            //     data: 'actions',
            //     name: 'actions',
            //     orderable: false,
            //     searchable: false,
            //     render: function(data, type, row) {
            //         return '<a href="/edit/'+row.id+'" class="btn btn-warning btn-sm">Edit</a>';
            //     }
            // }
        ]
    });
       $('#search-name').on('keyup', function() {
        table.draw();
    });


});



$(document).ready(function() {
    let table = $('#verifiedphotoTableList').DataTable({
        processing: true,
        serverSide: true,
         scrollX: true,
         ajax: window.APP_URL + '/admin/photos/showdata/' +  window.PHOTO_ID,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'ip_address', name: 'ip_address'},
            { data: 'browser', name: 'browser' },
            { data: 'platform', name: 'platform' },
            { data: 'device', name: 'device' },
            { data: 'device_type', name: 'device_type' },
            { data: 'referer', name: 'referer'},
            { data: 'user_agent', name: 'user_agent'},
            { data: 'country', name: 'country'},
            { data: 'country_code', name: 'country_code'},
            { data: 'region', name: 'region'},
            { data: 'region_name', name: 'region_name'},
            { data: 'city', name: 'city'},
            { data: 'zip', name: 'zip'},
            { data: 'latitude', name: 'latitude'},
            { data: 'longitude', name: 'longitude'},
            { data: 'timezone', name: 'timezone'},
            { data: 'isp', name: 'isp'},
            { data: 'org', name: 'org'},
            { data: 'as_name', name: 'as_name'},
        ]
    });


});



});
