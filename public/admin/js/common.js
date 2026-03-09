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
            { data: 'state', name: 'state' },
            { data: 'country', name: 'country' },
            { data: 'state', name: 'state' },
             { data: 'city', name: 'city' },
            { data: 'zip', name: 'zip' },
            // { data: 'phone_number', name: 'phone_number' },
            { data: 'device', name: 'device'},
            { data: 'timezone', name: 'timezone'},
            { data: 'created_at', name: 'created_at'},
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
            { data:'created_at', name:'created_at'},
            { data: 'view_count', name: 'view_count', orderable: false, searchable: false },
            { data:'status', name:'status'},
            //  { data:'upload_track_record', name:'upload_track_record'},
              {
            data: null,
            name: 'action',
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
                return `
                    <button class="btn btn-info btn-sm viewTrackBtn">
                        View
                    </button>
                `;
            }
        },
            // { data: 'actions', name: 'actions', orderable: false, searchable: false }
            
        ]
    });

    $(document).on('click', '.viewTrackBtn', function () {

    var tr = $(this).closest('tr');

    if (tr.hasClass('child')) {
        tr = tr.prev();
    }

    var rowData = table.row(tr).data();
    if (!rowData) return;

      const ips = [...new Set((rowData.user?.photo_upload_tracks || [])
                .map(t => t.ip_address)
                .filter(Boolean))];

    const ispList = [...new Set((rowData.user?.photo_upload_tracks || [])
                        .map(t => t.isp)
                        .filter(Boolean)
                    )].join(', ');

    // var html = `
    //      <b>Random Id:</b> ${rowData.random_id ?? ''}<br>
    //             <b>Date & Time:</b> ${rowData.word_api_date_time ?? ''}<br>
    //             <b>Location:</b> ${rowData.word_api_date_time ?? ''}<br>
    //             <b>Country:</b> ${rowData.country ?? ''}<br>
    //             <b>Region:</b> ${rowData.country ?? ''}<br>
    //             <b>City:</b> ${rowData.city ?? ''}<br>
    //             <b>Zip:</b> ${rowData.zip ?? ''}<br>
    //             <b>Timezone:</b> ${rowData.zip ?? ''}<br>
    //             <b>Latitude:</b> ${rowData.latitude ?? ''}<br>
    //             <b>Longitude:</b> ${rowData.longitude ?? ''}<br>
    //             <b>IP Address:</b> ${rowData.ip_address ?? ''}<br>
    //             <b>Device Type:</b> ${rowData.device_type ?? ''}<br>
    //             <b>Device Brand:</b> ${rowData.device_brand ?? ''}<br>
    //             <b>Device Model:</b> ${rowData.device_model ?? ''}<br>
    //             <b>Device Name:</b> ${rowData.device_name ?? ''}<br>
    //             <b>Device Manufacturer:</b> ${rowData.device_manufacturer ?? ''}<br>
    //             <b>Android Version:</b> ${rowData.android_version ?? ''}<br>
    //             <b>Android Sdk:</b> ${rowData.android_sdk ?? ''}<br>
    //             <b>IOS System Version:</b> ${rowData.ios_system_version ?? ''}<br>
    //             <b>IOS Identifier:</b> ${rowData.ios_identifier ?? ''}<br>
    //             <b>ISP:</b> ${rowData.isp ?? ''}<br>
    //     <hr>
     var html = `
       ${rowData.random_id ? `<b>Random Id:</b> ${rowData.random_id}<br>` : ''}
                ${rowData.word_api_date_time ? `<b>Date & Time:</b> ${rowData.word_api_date_time}<br>` : ''}
                ${rowData.location ? `<b>Location:</b> ${rowData.location}<br>` : ''}
                ${rowData.latitude ? `<b>Latitude:</b> ${rowData.latitude}<br>` : ''}
                ${rowData.longitude ? `<b>Longitude:</b> ${rowData.longitude}<br>` : ''}
                ${ips.length ? `<b>IP Address:</b> ${ips.join(', ')}<br>` : ''}
                ${rowData.device_type ? `<b>Device Type:</b> ${rowData.device_type}<br>` : ''}
                ${rowData.timezone ? `<b>Timezone:</b> ${rowData.timezone}<br>` : ''}  
                ${rowData.device_brand ? `<b>Device Brand:</b> ${rowData.device_brand}<br>` : ''}
                ${rowData.device_model ? `<b>Device Model:</b> ${rowData.device_model}<br>` : ''}
                ${rowData.device_name ? `<b>Device Name:</b> ${rowData.device_name}<br>` : ''}  
                ${rowData.device_manufacturer ? `<b>Device Manufacturer:</b> ${rowData.device_manufacturer}<br>` : ''} 
                ${rowData.android_version ? `<b>Android Version:</b> ${rowData.android_version}<br>` : ''}
                ${rowData.android_sdk ? `<b>Android Sdk:</b> ${rowData.android_sdk}<br>` : ''}
                ${rowData.ios_system_version ? `<b>IOS System Version:</b> ${rowData.ios_system_version}<br>` : ''}
                ${rowData.ios_identifier ? `<b>IOS Identifier:</b> ${rowData.ios_identifier}<br>` : ''}
                ${rowData.isp ? `<b>ISP:</b> ${rowData.isp}<br>` : ''}
               ${ispList ? `<b>ISP:</b> ${ispList}<br>` : ''}
                 <hr>
                 ${rowData.photo_url ? `<b>Image:</b><br>
                    <img src="${rowData.photo_url}" style=" height:350px; border-radius:6px;">
                    <br>` : ''}
        <hr>
        ${rowData.latitude && rowData.longitude ? `
            <iframe 
                width="100%" 
                height="350" 
                style="border:0;" 
                loading="lazy"
                src="https://maps.google.com/maps?q=${rowData.latitude},${rowData.longitude}&z=15&output=embed">
            </iframe>
        ` : '<p style="color:red;">Location not available</p>'}
    `;

    $('#commonheader').html('Track Details');
    $('#commonModalBody').html(html);

    var modal = new bootstrap.Modal(document.getElementById('commonModal'));
    modal.show();
});
        // STATUS TOGGLE
$('#photoTableList').on('click', '.toggle-state', function () {
    let id = $(this).data('id');
    let state = $(this).data('state');
    if (confirm("Are you sure you want to change the status?")) {
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
            { data: 'random_id', name: 'random_id' },
            { data: 'name', name: 'name' },
            { data: 'location', name: 'location' },
            { data: 'email', name: 'email' },
            { data: 'created_at', name: 'created_at' },
          
            {data:'view_count', name:'view_count'},
           {
            data: null,
            name: 'action',
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
                return `
                    <button class="btn btn-info btn-sm viewTrackBtn">
                        View
                    </button>
                `;
            }
        },
         {data: 'status', name: 'status' },
        ]
        });
        $('#search-name').on('keyup', function() {
            table.draw();
        });
        var globalMap = null;
        $(document).on('click', '.viewTrackBtn', function () {

            var table = $('#photodataTableList').DataTable();

            // Get correct row (works even in responsive mode)
            var tr = $(this).closest('tr');

            if (tr.hasClass('child')) {
                tr = tr.prev(); // if responsive child row
            }

            var rowData = table.row(tr).data();
            if (!rowData) {
                console.log("Row data not found");
                return;
            }

            //  var html = `
            //     <b>Random Id:</b> ${rowData.random_id ?? ''}<br>
            //     <b>Date & Time:</b> ${rowData.word_api_date_time ?? ''}<br>
            //     <b>Location:</b> ${rowData.word_api_date_time ?? ''}<br>
            //     <b>Country:</b> ${rowData.country ?? ''}<br>
            //     <b>Region:</b> ${rowData.country ?? ''}<br>
            //     <b>City:</b> ${rowData.city ?? ''}<br>
            //     <b>Zip:</b> ${rowData.zip ?? ''}<br>
            //     <b>Timezone:</b> ${rowData.zip ?? ''}<br>
            //     <b>Latitude:</b> ${rowData.latitude ?? ''}<br>
            //     <b>Longitude:</b> ${rowData.longitude ?? ''}<br>
            //     <b>IP Address:</b> ${rowData.ip_address ?? ''}<br>
            //     <b>Device Type:</b> ${rowData.device_type ?? ''}<br>
            //     <b>Device Brand:</b> ${rowData.device_brand ?? ''}<br>
            //     <b>Device Model:</b> ${rowData.device_model ?? ''}<br>
            //     <b>Device Name:</b> ${rowData.device_name ?? ''}<br>
            //     <b>Device Manufacturer:</b> ${rowData.device_manufacturer ?? ''}<br>
            //     <b>Android Version:</b> ${rowData.android_version ?? ''}<br>
            //     <b>Android Sdk:</b> ${rowData.android_sdk ?? ''}<br>
            //     <b>IOS System Version:</b> ${rowData.ios_system_version ?? ''}<br>
            //     <b>IOS Identifier:</b> ${rowData.ios_identifier ?? ''}<br>
            //     <b>ISP:</b> ${rowData.isp ?? ''}<br>
            //     <hr>
            var html = `
                 ${rowData.random_id ? `<b>Random Id:</b> ${rowData.random_id}<br>` : ''}
                ${rowData.word_api_date_time ? `<b>Date & Time:</b> ${rowData.word_api_date_time}<br>` : ''}
                ${rowData.location ? `<b>Location:</b> ${rowData.location}<br>` : ''}
                ${rowData.latitude ? `<b>Latitude:</b> ${rowData.latitude}<br>` : ''}
                ${rowData.longitude ? `<b>Longitude:</b> ${rowData.longitude}<br>` : ''}
                ${rowData.ip_address ? `<b>IP Address:</b> ${rowData.ip_address}<br>` : ''}
                ${rowData.device_type ? `<b>Device Type:</b> ${rowData.device_type}<br>` : ''}
                ${rowData.timezone ? `<b>Timezone:</b> ${rowData.timezone}<br>` : ''}  
                ${rowData.device_brand ? `<b>Device Brand:</b> ${rowData.device_brand}<br>` : ''}
                ${rowData.device_model ? `<b>Device Model:</b> ${rowData.device_model}<br>` : ''}
                ${rowData.device_name ? `<b>Device Name:</b> ${rowData.device_name}<br>` : ''}  
                ${rowData.device_manufacturer ? `<b>Device Manufacturer:</b> ${rowData.device_manufacturer}<br>` : ''} 
                ${rowData.android_version ? `<b>Android Version:</b> ${rowData.android_version}<br>` : ''}
                ${rowData.android_sdk ? `<b>Android Sdk:</b> ${rowData.android_sdk}<br>` : ''}
                ${rowData.ios_system_version ? `<b>IOS System Version:</b> ${rowData.ios_system_version}<br>` : ''}
                ${rowData.ios_identifier ? `<b>IOS Identifier:</b> ${rowData.ios_identifier}<br>` : ''}
                ${rowData.isp ? `<b>ISP:</b> ${rowData.isp}<br>` : ''}
                <hr>
                 ${rowData.image ? `<b>Image:</b><br>
                    <img src="${rowData.image}" style="height:350px; border-radius:6px;">
                    <br>` : ''}
                <hr>
                ${rowData.latitude && rowData.longitude ? `
                    <iframe 
                        width="100%" 
                        height="350" 
                        style="border:0;" 
                        loading="lazy"
                        src="https://maps.google.com/maps?q=${rowData.latitude},${rowData.longitude}&z=15&output=embed">
                    </iframe>
                ` : '<p style="color:red;">Location not available</p>'}
            `;

            $('#commonheader').html('Track Details');
            $('#commonModalBody').html(html);

            var modal = new bootstrap.Modal(document.getElementById('commonModal'));
            modal.show();
    });

    $('#photodataTableList').on('click', '.toggle-state', function () {
        let id = $(this).data('id');
        let state = $(this).data('state');
        if (confirm("Are you sure you want to change the status?")) {
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
            // { data: 'device', name: 'device' },
            { data: 'device_type', name: 'device_type' },
            // { data: 'referer', name: 'referer'},
            { data: 'user_agent', name: 'user_agent'},
            { data: 'country', name: 'country'},
            // { data: 'country_code', name: 'country_code'},
            // { data: 'region', name: 'region'},
            { data: 'region_name', name: 'region_name'},
            { data: 'city', name: 'city'},
            // { data: 'zip', name: 'zip'},
            { data: 'latitude', name: 'latitude'},
            { data: 'longitude', name: 'longitude'},
            { data: 'timezone', name: 'timezone'},
            // { data: 'isp', name: 'isp'},
            { data: 'org', name: 'org'},
            { data: 'as_name', name: 'as_name'},
            { data: 'created_at', name: 'created_at'},
        ]
    });


});

  // var $j = jQuery.noConflict();
$(document).ready(function() {
    let table = $('#userList').DataTable({
        processing: true,
        serverSide: true,
        ajax: window.APP_URL + '/admin/users/data/list/',  // Ensure this URL is correct

        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'profile_image', name: 'profile_image', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone_number', name: 'phone_number' },
            { data: 'last_login_at', name: 'last_login_at' },
            { data: 'created_at', name: 'created_at'},
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    // STATUS TOGGLE
$('#userList').on('click', '.toggle-status', function () {
    let id = $(this).data('id');
    let status = $(this).data('status');
    if (confirm("Are you sure you want to change the status?")) {
       $.ajax({
    url: window.APP_URL + '/admin/update/users/status',
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
    $('#userList').on('click', '.delete-user', function () {
        let id = $(this).data('id');
        if (confirm("Are you sure you want to delete this admin user?")) {
            $.get(window.APP_URL + '/admin/update/users/status', {
                id: id,
                status: -1
            }, function () {
                table.ajax.reload(null, false);
            });
        }
    });
});



$(document).ready(function() {
    let table = $('#activityList').DataTable({
        processing: true,
        serverSide: true,
        ajax: window.APP_URL + '/admin/activity/list/',  // Ensure this URL is correct

        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'admin', name: 'admin'},
            { data: 'action', name: 'action' },
            { data: 'module', name: 'module' },
            { data: 'description', name: 'description' },
            { data: 'ip_address', name: 'ip_address'},
            { data: 'date', name: 'date'},
        ]
    });
});


$(document).ready(function() {

// Fetch notifications and update modal
function fetchNotifications() {
    $.ajax({
        url: window.APP_URL + '/admin/notifications/unread',
        type: 'GET',
        success: function(data) {
            // Update badge count
            let count = data.length;
            if(count > 0) {
                $('#notificationCount').text(count).show();
            } else {
                $('#notificationCount').hide();
            }

            // Build modal content
            if(data.length === 0) {
                $('#notificationModalBody').html('<p>No new notifications.</p>');
            } else {
                let html = '<ul class="list-group">';
                data.forEach(function(item) {
                    html += `
                        <li class="list-group-item notificationRow d-flex justify-content-between align-items-start bg-light" 
                            data-id="${item.id}" style="cursor: pointer;">
                            <div class="notification-text">
                                <b>${item.name}</b><br>
                                <small>${toTitleCase(item.type)}</small><br>
                                <small class="text-muted">${item.created_at_formatted}</small>
                            </div>
                        </li>
                    `;
                });
                html += '</ul>';
                $('#notificationModalBody').html(html);
            }
        },
        error: function() {
            $('#notificationModalBody').html('<p class="text-danger">Failed to load notifications.</p>');
        }
    });
}


// Call once on page load to show count
fetchNotifications();

// When bell icon is clicked, fetch notifications and show modal
$('#notificationBell').on('click', function() {
    fetchNotifications();

    // Show Bootstrap modal
    var notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
    notificationModal.show();
});

// Mark notification as read when clicking on row
$(document).on('click', '.notificationRow', function () {
    window.location.href = window.APP_URL + '/admin/notifications';
});

// Optional: auto-refresh badge count every 15 seconds
setInterval(fetchNotifications, 15000);
});


$(document).ready(function() {

    $(document).on('click', '.notification-item', function() {
        let row = $(this);
        let id = row.data('id');
        let isRead = row.data('is-read');

        // If already read, just navigate
        if (isRead) {
            window.location.href = `/admin/notifications/${id}`;
            return;
        }

        // AJAX call to mark as read
        $.ajax({
            url: `/admin/notifications/read/${id}`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                // Update row to show as read
                row.removeClass('bg-light border-start border-4 border-primary').addClass('bg-white');
                row.data('is-read', 1);

                // Decrease badge count
                let badge = $('#notificationBadge'); // your badge ID
                let count = parseInt(badge.text()) || 0;
                if (count > 0) badge.text(count - 1);

                // Redirect to the notification details page
                window.location.href = `/admin/notifications/${id}`;
            },
            error: function() {
                alert('Failed to mark notification as read.');
            }
        });
    });

});


$(document).ready(function() {
    let table = $('#reportImagesList').DataTable({
         responsive: true,
        scrollX: true,
        autoWidth: false,
        processing: true,
        serverSide: true,
        ajax: window.APP_URL + '/admin/reported/images/list',  // Ensure this URL is correct

        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'image', name: 'image'},
            { data: 'photo_random_id', name: 'photo_random_id'},
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'message', name: 'message' },
            { data: 'ip_address', name: 'ip_address'},
            { data: 'device_type', name: 'device_type'},
            { data: 'country', name: 'country'},
            { data: 'region', name: 'region'},
            { data: 'city', name: 'city'},
            { data: 'zip', name: 'zip'},
            { data: 'created_at', name: 'created_at'},
            { data: 'actions', name: 'actions'},
            
        ]
    });
});


$(document).ready(function() {
    let table = $('#notificationList').DataTable({
        processing: true,
        serverSide: true,   
        ajax: {
            url: window.APP_URL + '/admin/notificationList/list/', // Ensure this URL is correct
            type: 'GET', // Use GET method to pass query parameters
            data: function(d) {
                // Get the selected type filter value and append it to the request data
                d.type = $('#typeFilter').val();  // Add type filter to the request
            }
        },

        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'photo_random_id', name: 'photo_random_id'},
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'message', name: 'message' },
            { data: 'type', name: 'type' },
            { data: 'ip_address', name: 'ip_address'},
            { data: 'date', name: 'date'},
            { data:'actions',name: 'actions'}
        ]
    });

    // Trigger DataTable reload when the filter changes
    $('#typeFilter').change(function () {
        table.ajax.reload();  // Reload the table with the new filter value
    });
});

function toTitleCase(str) {
    return str.toLowerCase().split(' ').map(word => 
        word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ');
}

// Example
item.type = toTitleCase(item.type);


});
