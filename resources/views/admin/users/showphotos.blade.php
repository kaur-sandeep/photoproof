@extends('admin.layouts.master')

@section('title', 'Users List')

@section('content')


        <!-- AdminLTE CSS -->
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta/dist/css/adminlte.min.css"> -->

        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
     

<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{$user->email}}</h3>
        </div>

        <div class="card-body">
            <table id="userTableList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Images</th>
                        <th>View</th>
                        <th>Upload Track Details</th>
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
       ajax: {
            url: "{{ route("admin.user.images.by.id", ["id" => $user->id])  }}",

            
            type: 'GET',
            data: function(d) {
                d.name = $j('#search-name').val();  // Pass search value to the backend
            }
        },

        columns: [
            { data: 'serial_number', name: 'serial_number' },
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
            {data:'view_count', name:'view_count'},
            {
                data: 'upload_track_details',
                name: 'upload_track_details',
                // orderable: false,
                // searchable: false,
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
       $j('#search-name').on('keyup', function() {
        table.draw();
    });


});


</script>

@endsection
