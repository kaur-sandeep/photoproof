@extends('admin.layouts.master')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Photos {{ request('organization_name') ? '— '.request('organization_name') : '' }}</h4>
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary">&larr; Back</a>
    </div>
    <div class="card-body">
        <table id="photoTableList" class="table table-bordered w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Random Id</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>User</th>
                    <th>Created At</th>
                    <th>Views</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/photos-list.js') }}"></script> {{-- same JS file as your main photos page --}}
@endpush