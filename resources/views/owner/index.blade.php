@extends('admin.layouts.master')

@section('title', 'Employees  List')

@section('content')

<div class="container-fluid">
    <div class="admin-page-header">

<h3 class="card-title"><b>Employees List </b></h3>
             <a href="{{ route('owner.employee.create') }}" class="btn btn-primary mb-3" style="float:right">Add Employee</a>

    </div>
    <div class="card">
       
        <div class="card-body">
            <table id="employeesList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Name</th>
                        <th>Employee Email</th>
                        <th>Employee Phone</th>
                        <th>Photos</th>
>                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
@endsection