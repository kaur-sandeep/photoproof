@extends('admin.layouts.master')

@section('title', 'App Organization Users List')

@section('content')
<style>
    .pull-right{
        float:right;
    }
    </style>
<div class="container-fluid">
    <div class="admin-page-header">
        <h3 class="card-title"><b>App Organization Users List </b></h3>
    </div>
    <div class="card">
        <div class="card-body">
           <table id="organizationemployeesList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Name</th>
                        <th>Employee Email</th>
                        <th>Employee Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
@endsection