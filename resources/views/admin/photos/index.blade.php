@extends('admin.layouts.master')

@section('title', 'Photos List')

@section('content')


        <!-- AdminLTE CSS -->
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta/dist/css/adminlte.min.css"> -->

        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
     

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Photos List</h3>
        </div>

        <div class="card-body">
            <table id="userTableList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Random ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>User Email</th>
                        <th>View Count</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>

@endsection


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<!-- Bootstrap JS (optional but recommended) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var $j = jQuery.noConflict();
$j(document).ready(function() {
    let table = $j('#userTableList').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.photos.list') }}",  // Ensure this URL is correct

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
$j('#userTableList').on('click', '.toggle-state', function () {
    let id = $j(this).data('id');
    let state = $j(this).data('state');
    if (confirm("Are you sure?")) {
       $j.ajax({
    url: "{{ route('admin.photos.update.status') }}",
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
    $j('#userTableList').on('click', '.delete-user', function () {
        let id = $j(this).data('id');
        if (confirm("Are you sure you want to delete this user?")) {
            $j.get("{{ route('admin.photos.update.status') }}", {
                _token: "{{ csrf_token() }}",
                id: id,
                state: -1
            }, function () {
                table.ajax.reload(null, false);
            });
        }
    });

});


</script>

