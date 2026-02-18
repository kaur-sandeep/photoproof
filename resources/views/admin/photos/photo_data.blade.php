@extends('admin.layouts.master')

@section('title', 'Viewers Details')

@section('content')
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
            <h3 class="card-title"><b>View/Verified Photo List({{$count}})</b></h3>
        </div>

        <div class="card-body">
            <table id="verifiedphotoTableList" class="table table-bordered table-striped">
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

<script>
    window.PHOTO_ID = {{ $id }};
</script>

@endsection
