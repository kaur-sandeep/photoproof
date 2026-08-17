@extends('admin.layouts.master')

@section('title', 'Corporate Accounts List')

@section('content')
<style>
    #organizationList td {
        white-space: normal !important;
        word-break: break-word;
        overflow-wrap: break-word;
        vertical-align: top;
    }

    /* Message column */
    #organizationList td:nth-child(8),
    #organizationList th:nth-child(8) {
        max-width: 250px;
        width: 250px;
    }
</style>

<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="admin-page-header">

<h3 class="card-title"><b>Corporate Accounts List </b></h3>
             <a href="{{ route('admin.organization.create') }}" class="btn btn-primary mb-3" style="float:right">Add Corporate Account</a>

    </div>
    <div class="card">
       
        <div class="card-body">
            <table id="organizationList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <!-- <th>#</th> -->
                        <th>Company Name</th>
                        <th>Email</th>
                        <th>Plan Name</th>
                         <th>Monthly Photo Limit</th>
                        <!-- <th>Organization Code</th> -->
                        <th>Total Employees</th>
                        <th>Total Photos</th>
                        <!-- <th>Message</th> -->
                        <th>Created at</th>
                         <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>


<div class="modal fade" id="organizationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Company Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <table class="table table-bordered">

                    <tr>
                        <th>Company Name</th>
                        <td id="m_name"></td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td id="m_email"></td>
                    </tr>

                    <tr>
                        <th>Plan</th>
                        <td id="m_plan"></td>
                    </tr>

                    <tr>
                        <th>Monthly Photo Limit</th>
                        <td id="m_limit"></td>
                    </tr>

                    <tr>
                        <th>Total Employees</th>
                        <td id="m_employee"></td>
                    </tr>

                    <tr>
                        <th>Photos Uploaded</th>
                        <td id="m_photo"></td>
                    </tr>

                    <tr>
                        <th>Message</th>
                        <td id="m_message"></td>
                    </tr>

                    <tr>
                        <th>Created At</th>
                        <td id="m_created"></td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td id="m_status"></td>
                    </tr>

                </table>

            </div>

        </div>
    </div>
</div>



@endsection
