@extends('admin.layouts.master')
@section('title', 'Subscription History')
@section('content')
<div class="container-fluid">
       <div class="admin-page-header">
        <h3 class="card-title"><b>Subscription History</b></h3>
    </div>
    <div class="card"><div class="card-body"><div class="table-responsive">
        <table id="subscriptions-table" class="table table-bordered table-striped align-middle w-100">
            <thead><tr><th>Organization</th><th>Email</th><th>Plan</th><th>Start</th><th>Expiry</th><th>Monthly</th><th>Top-up</th><th>Status</th></tr></thead>
        </table>
    </div></div></div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.jQuery('#subscriptions-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.billing.subscriptions.data') }}',
        columns: [
            { data: 'organization_name', name: 'organization_name' }, { data: 'email', name: 'email' },
            { data: 'plan_name', name: 'plan_name' }, { data: 'starts_at', name: 'starts_at' },
            { data: 'expires_at', name: 'expires_at' }, { data: 'monthly_usage', name: 'monthly_usage', searchable: false },
            { data: 'topup_usage', name: 'topup_usage', searchable: false }, { data: 'subscription_status', name: 'subscription_status', searchable: false },
        ],
    });
});
</script>
@endsection
