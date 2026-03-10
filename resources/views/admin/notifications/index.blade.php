@extends('admin.layouts.master')

@section('title', 'Notifications List')

@section('content')

<div class="container-fluid">
    <div class="admin-page-header">
        <h3 class="card-title"><b>Notifications List </b></h3>
    </div>
    <div class="card">
        <div class="card-body">
            
            <div class="row mb-3 align-items-center">
                <div class="col-md-4">
                <label class="form-label fw-bold">Filter By Type</label>
                <select id="typeFilter" class="form-select shadow-sm">
                <option value="">All Types</option>    
                    @foreach($types   as $type)
                       <option value="{{ $type }}">{{ ucwords($type) }}</option>
                    @endforeach
                </select>
                </div>
            </div>
            <table id="notificationList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Random Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
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

<div class="modal fade" id="shwonotificationModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title"><b>Notification Details</b></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- <p><strong>Name:</strong> <span id="modal_name"></span></p>
        <p><strong>Email:</strong> <span id="modal_email"></span></p>
        <p><strong>Messasge:</strong> <span id="modal_message"></span></p>
        <p><strong>IP Address:</strong> <span id="modal_ip"></span></p>
        <p><strong>Type:</strong> <span id="modal_type"></span></p>
        <p><strong>Date:</strong> <span id="modal_date"></span></p> -->

        <div id="row_name">
<strong>Name:</strong> <span id="name"></span>
</div>

<div id="row_email">
<strong>Email:</strong> <span id="email"></span>
</div>

<div id="row_message">
<strong>Message:</strong> <span id="message"></span>
</div>

<div id="row_browser">
<strong>Browser:</strong> <span id="browser"></span>
</div>

<div id="row_platform">
<strong>Platform:</strong> <span id="platform"></span>
</div>

<div id="row_device">
<strong>Device:</strong> <span id="device"></span>
</div>

<div id="row_ip">
<strong>IP Address:</strong> <span id="ip"></span>
</div>

<div id="row_date">
<strong>Date:</strong> <span id="date"></span>
</div>
<div id="row_type">
<strong>Type:</strong> <span id="type"></span>
</div>


<div id="row_location">
<strong>Location:</strong> <span id="location"></span>
</div>

      </div>

    </div>
  </div>
</div>

@endsection