@extends('admin.layouts.master')

@section('title', 'Admin Users List')

@section('content')

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><b>Admin Users List </b></h3>
             <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3" style="float:right">Add Admin User</a>
        </div>
        <div class="card-body">
            <table id="userList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Last Login at</th>
                        <th>Registered On</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
@endsection