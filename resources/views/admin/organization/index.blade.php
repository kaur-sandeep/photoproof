@extends('admin.layouts.master')

@section('title', 'Organizations List')

@section('content')

<div class="container-fluid">
    <div class="admin-page-header">

<h3 class="card-title"><b>Organizations List </b></h3>
             <a href="{{ route('admin.organization.create') }}" class="btn btn-primary mb-3" style="float:right">Add Organization</a>

    </div>
    <div class="card">
       
        <div class="card-body">
            <table id="organizationList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Organization Name</th>
                        <th>Email Address</th>
                        <th>Organization Code</th>
                        <th>Current Subscription Plan</th>
                        <th>Monthly Photo Limit</th>
                        <th>Organization Status</th>
                        <th>Creation Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
@endsection