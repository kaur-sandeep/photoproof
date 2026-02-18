@extends('admin.layouts.master')

@section('title', 'Users List')

@section('content')


        <!-- AdminLTE CSS -->
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta/dist/css/adminlte.min.css"> -->

        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
     

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Users List</h3>
        </div>

        <div class="card-body">
            <table id="userTableList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <!-- <th>Profile</th> -->
                        <!-- <th>Name</th> -->
                        <th>Email</th>
                        <!-- <th>Phone</th> -->
                        <th>Photos</th>
                        <!-- <th>Status</th>
                        <th>Actions</th> -->
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>



<script>
    var usersListUrl = "{{ route('admin.users.list') }}";
    var usersUpdateUrl = "{{ route('admin.users.update.data') }}";
</script>

@endsection