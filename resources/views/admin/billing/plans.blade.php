@extends('admin.layouts.master')
@section('title', 'Plan Management')
@section('content')
<div class="container-fluid">
    <div class="admin-page-header d-flex justify-content-between align-items-center mb-3"><h3 class="mb-0"><b>Plans</b></h3><a class="btn btn-primary" href="{{ route('admin.billing.plans.create') }}">+ Add Plan</a></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card"><div class="card-body"><div class="table-responsive">
        <table id="plans-table" class="table table-bordered table-striped align-middle w-100"><thead><tr><th>Plan Name</th><th>Description</th><th>Monthly Price</th><th>Yearly Price</th><th>Photo Limit</th><th>Purchased Users</th><th>State</th><th>Actions</th></tr></thead></table>
    </div></div></div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.jQuery('#plans-table').DataTable({ processing: true, serverSide: true, ajax: '{{ route('admin.billing.plans.data') }}', columns: [
            { data: 'name', name: 'name' }, { data: 'description', name: 'description', defaultContent: '' },
            { data: 'monthly_price', name: 'monthly_price' }, { data: 'yearly_price', name: 'yearly_price' },
            { data: 'monthly_photo_limit', name: 'monthly_photo_limit' }, { data: 'purchasers_count', name: 'purchasers_count', searchable: false },
            { data: 'state', name: 'state', orderable: false, searchable: false }, { data: 'actions', name: 'actions', orderable: false, searchable: false },
        ] });
    });
</script>
@endsection
