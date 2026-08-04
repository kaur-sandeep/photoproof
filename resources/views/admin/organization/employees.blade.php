@extends('admin.layouts.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Employees {{ request('organization_name') ? '— '.request('organization_name') : '' }}</h4>
        <!-- <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary">&larr; Back</a> -->
    </div>
    <div class="card-body">
        <table id="userTableList" class="table table-bordered w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>State</th>
                    <th>City</th>
                    <th>Zip</th>
                    <th>Device</th>
                    <th>Organization</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th>Photos</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
{{-- paste the SAME js file/script you already use for the main users list --}}
<script src="{{ asset('js/admin/users-list.js') }}"></script> {{-- or wherever it lives --}}
@endpush