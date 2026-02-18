@extends('admin.layouts.master')

@section('title', 'Viewers Details')

@section('content')


        <!-- AdminLTE CSS -->
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta/dist/css/adminlte.min.css"> -->

        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
     
    <style>
            table {
            width: 100% !important;
        }

        table th {
            white-space: nowrap; /* Full column name show karega */
        }

        table td {
            white-space: normal; /* Full data wrap hoga */
            word-break: break-word;
        }
    </style>

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Viewers Details</h3>
        </div>

        <div class="card-body">
            <table id="userTableList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>IP Address</th>
                        <th>Browser</th>
                        <th>Platform</th>
                        <th>Device</th>
                        <th>Device Type</th>
                        <th>Referer</th>
                        <th>User Agent</th>
                        <th>Country</th>
                        <th>Country Code</th>
                        <th>Region </th>
                        <th>Region Name</th>
                        <th>City</th>
                        <th>Zip</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Timezone</th>
                        <th>ISP</th>
                        <th>Org</th>
                        <th>As Name</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<!-- Bootstrap JS (optional but recommended) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    
    var $j = jQuery.noConflict();
$j(document).ready(function() {
    let table = $j('#userTableList').DataTable({
        processing: true,
        serverSide: true,
         scrollX: true,
        ajax: "{{  route("admin.photos.showdata", ["id" => $id])  }}",  // Ensure this URL is correct

        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'ip_address', name: 'ip_address'},
            { data: 'browser', name: 'browser' },
            { data: 'platform', name: 'platform' },
            { data: 'device', name: 'device' },
            { data: 'device_type', name: 'device_type' },
            { data: 'referer', name: 'referer'},
            { data: 'user_agent', name: 'user_agent'},
            { data: 'country', name: 'country'},
            { data: 'country_code', name: 'country_code'},
            { data: 'region', name: 'region'},
            { data: 'region_name', name: 'region_name'},
            { data: 'city', name: 'city'},
            { data: 'zip', name: 'zip'},
            { data: 'latitude', name: 'latitude'},
            { data: 'longitude', name: 'longitude'},
            { data: 'timezone', name: 'timezone'},
            { data: 'isp', name: 'isp'},
            { data: 'org', name: 'org'},
            { data: 'as_name', name: 'as_name'},
        ]
    });


});


</script>
@endsection
