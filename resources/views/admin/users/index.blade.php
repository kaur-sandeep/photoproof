@extends('admin.layouts.master')

@section('title', 'Users List')

@section('content')

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><b>Users List</b></h3>
        </div>

        <div class="card-body">
            <table id="userTableList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Email</th>
                        <!-- <th>Phone</th> -->
                        <th>Photos</th>
                        <th>Device</th>
                        <th>Registered On</th>
                        <!-- <th>Status</th>
                        <th>Actions</th> -->
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
@endsection