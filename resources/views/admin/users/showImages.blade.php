@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')

     @push('styles')
        <!-- AdminLTE CSS -->
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta/dist/css/adminlte.min.css"> -->

        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
        @endpush


        <style>
            #userTable_filter .dataTables_filter {
            float: right;
         }
    </style>
      <div class="content-wrapper">
        <div class="container-fluid">

            <!-- Search Input for filtering by name -->
            <div class="row mb-4">
                <div class="col-sm-12">
                    <label for="search-name">Search by Name:</label>
                    <input type="text" id="search-name" class="form-control" placeholder="Search users by name">
                </div>
            </div>

            <!-- DataTable -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users List</h3>
                </div>
                <div class="card-body">
                    <table id="userTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Images</th>
                                <th>Upload Track Details</th>
                                <!-- <th>Actions</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated here via Yajra DataTables -->
                        </tbody>
                    </table>
                </div>
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
    var table = $j('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route("admin.user.images")  }}",

            
            type: 'GET',
            data: function(d) {
                d.name = $j('#search-name').val();  // Pass search value to the backend
            }
        },
        columns: [
            // { data: 'name', name: 'name' },
            // { data: 'email', name: 'email' },
            {
                data: 'images',
                name: 'images',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    if (data) {
                        return data;
                    } else {
                        return '<span>No images available</span>';
                    }
                }
            },
            {
                data: 'upload_track_details',
                name: 'upload_track_details',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    if (data) {
                        return data;
                    } else {
                        return '<span>No upload data available</span>';
                    }
                }
            }
            // ,
            // {
            //     data: 'actions',
            //     name: 'actions',
            //     orderable: false,
            //     searchable: false,
            //     render: function(data, type, row) {
            //         return '<a href="/edit/'+row.id+'" class="btn btn-warning btn-sm">Edit</a>';
            //     }
            // }
        ]
    });

    // Trigger table redraw when the search input changes
    $j('#search-name').on('keyup', function() {
        table.draw();
    });
});

    </script>

@endsection
