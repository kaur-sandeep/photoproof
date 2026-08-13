@extends('admin.layouts.master')
@section('title', 'Orders')
@section('content')
<div class="container-fluid"><h3>Orders</h3>@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Order</th><th>Item</th><th>Type</th><th>Amount</th><th>Order</th><th>Payment</th><th>Date</th></tr></thead><tbody>@forelse($orders as $order)<tr><td>{{ $order->order_number }}</td><td>{{ $order->subscriptionPlan?->name ?? $order->topupPlan?->name }}</td><td>{{ ucfirst($order->order_type) }}</td><td>₹{{ $order->amount }}</td><td>{{ ucfirst($order->status) }}</td><td>{{ ucfirst($order->payment_status) }}</td><td>{{ $order->created_at->format('d M Y') }}</td></tr>@empty<tr><td colspan="7">No orders yet.</td></tr>@endforelse</tbody></table></div></div>{{ $orders->links() }}</div>
@endsection
