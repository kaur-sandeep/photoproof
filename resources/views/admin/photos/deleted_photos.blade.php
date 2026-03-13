@extends('admin.layouts.master')

@section('title', 'Deleted Photos List')

@section('content')

<div class="container-fluid">
    <div class="admin-page-header">
        <h3 class="card-title"><b> Deleted Photos List </b></h3>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="deletedphotoTableList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Random ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>User Email</th>
                        <th>Deleted On</th>
                        <!-- <th>Viewed/Verified</th>
                        <th>Status</th>
                        <th>Track Details</th> -->
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection