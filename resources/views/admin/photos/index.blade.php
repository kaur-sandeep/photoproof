@extends('admin.layouts.master')

@section('title', 'Photos List')

@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><b>Photos List</b></h3>
        </div>

        <div class="card-body">
            <table id="photoTableList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Random ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>User Email</th>
                        <th>Created On</th>
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