@extends('admin.layouts.master')

@section('title', 'Notifications List')

@section('content')

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><b>Notifications List</b></h3>
        </div>
        

        <div class="card-body">
            
            <div class="row mb-3 align-items-center">
                <div class="col-md-4">
                <label class="form-label fw-bold">Filter By Type</label>
                <select id="typeFilter" class="form-select shadow-sm">
                <option value="">All Types</option>    
                    @foreach($notifications->unique('type') as $notification)
                        <option value="{{$notification->type}}">{{ ucwords($notification->type)}}</option>
                    @endforeach
                </select>
                </div>
            </div>
            <table id="notificationList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Random Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>IP Address</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@endsection