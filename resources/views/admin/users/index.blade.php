@extends('admin.layouts.master')

@section('title', 'App Users List')

@section('content')
<style>
    .pull-right{
        float:right;
    }
    </style>
<div class="container-fluid">
    <div class="admin-page-header">
        <h3 class="card-title"><b>App Users List </b></h3>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="userTableList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Location</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Zip</th>
                        <!-- <th>Phone</th> -->
                        <th>Device</th>
                        <!-- <th>Time Zone</th> -->
                        <th>Registered On</th>
                        <th>Photos</th>
                      
                        <!-- <th>Status</th>
                        <th>Actions</th> -->
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
@endsection