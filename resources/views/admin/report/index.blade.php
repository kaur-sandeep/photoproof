@extends('admin.layouts.master')

@section('title', 'Reported Images List')

@section('content')

<div class="container-fluid">

    <div class="card">
        <div class="card-body">
            <table id="reportImagesList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Photo</th>
                        <th>Photo ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>IP Address</th>
                        <th>Device</th>
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
@endsection