@extends('admin.layouts.master')
@section('title', 'Orders and Payments')
@section('content')
<div class="container-fluid">
     <div class="admin-page-header">
        <h3 class="card-title"><b>Orders &amp; Payments</b></h3>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    <div class="card"><div class="card-body"><div class="table-responsive">
        <table id="orders-table" class="table table-bordered table-striped align-middle w-100">
            <thead><tr><th>Order</th><th>Organization</th><th>Email</th><th>Item</th><th>Amount</th><th>Created at</th><th>Payment</th><th>Action</th></tr></thead>
        </table>
    </div></div></div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.jQuery('#orders-table').DataTable({
        processing: true,
        serverSide: true,
        order: [[5, 'desc']],
        ajax: { url: '{{ route('admin.billing.orders.data') }}', data: { plan: '{{ request('plan') }}', topup: '{{ request('topup') }}' } },
        columns: [
            { data: 'order_number', name: 'order_number' }, { data: 'organization_name', name: 'organization_name' },
            { data: 'email', name: 'email' }, { data: 'item', name: 'item' }, { data: 'amount', name: 'amount' },
            { data: 'created_at', name: 'created_at' },
            { data: 'payment_status', name: 'payment_status' }, { data: 'actions', orderable: false, searchable: false },
        ],
    });
});
</script>
@endsection
