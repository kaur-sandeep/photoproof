@extends('admin.layouts.master')

@section('title', 'Reported Images List')

@section('content')

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><b>Reported Images</b></h3>
        </div>
        <div class="card-body">
            <table id="reportImagesList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Photo ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>IP Address</th>
                        <th>Device Type</th>
                         <th>Country</th>
                        <th>Region</th>
                        <th>City</th>
                        <th>Zip</th>
                        <th>Reported On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>


<div class="modal fade" id="ReportModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title"><b>Reported Images Details</b></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- <p><strong>Name:</strong> <span id="modal_name"></span></p>
        <p><strong>Email:</strong> <span id="modal_email"></span></p>
        <p><strong>Messasge:</strong> <span id="modal_message"></span></p>
        <p><strong>Browser:</strong> <span id="modal_browser"></span></p>
        <p><strong>Platform:</strong> <span id="modal_platform"></span></p>
        <p><strong>Device Type:</strong> <span id="modal_deviceType"></span></p>
        <p><strong>IP Address:</strong> <span id="modal_ip"></span></p>
        <p><strong>Location :</strong> <span id="modal_location"></span></p>
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

<div id="row_location">
<strong>Location:</strong> <span id="location"></span>
</div>

      </div>

    </div>
  </div>
</div>
@endsection