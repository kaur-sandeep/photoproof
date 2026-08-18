@extends('admin.layouts.master')
@section('title', 'Orders')
@section('content')
<div class="container-fluid">
    <h3>Orders</h3>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card"><div class="card-body"><div class="table-responsive">
        <table id="owner-orders-table" class="table table-bordered table-striped align-middle w-100">
            <thead><tr><th>Order</th><th>Item</th><th>Type</th><th>Amount</th><th>Order Status</th><th>Payment</th><th>Date</th></tr></thead>
        </table>
    </div></div></div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.jQuery('#owner-orders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('owner.orders.data') }}',
        columns: [
            { data: 'order_number', name: 'order_number' },
            { data: 'item', name: 'item' },
            { data: 'order_type', name: 'order_type' },
            { data: 'amount', name: 'amount' },
            { data: 'status', name: 'status' },
            { data: 'payment_status', name: 'payment_status' },
            { data: 'created_at', name: 'created_at' },
        ],
    });
});
</script>
@endsection
