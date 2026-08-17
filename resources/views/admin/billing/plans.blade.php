@extends('admin.layouts.master')
@section('title', 'Plan Management')
@section('content')
<div class="container-fluid">
    <div class="admin-page-header"><h3><b>Plan Management</b></h3><button type="button" class="btn btn-primary" id="show-plan-form">Add Plan</button></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card mb-4 d-none" id="plan-form-card"><div class="card-body">
        <h5 class="mb-3">Add Corporate Plan</h5>
        <form class="row g-3" method="post" action="{{ route('admin.billing.plans.store') }}">@csrf
            <input type="hidden" name="type" value="organization">
            <div class="col-md-4"><label class="form-label">Plan name</label><input class="form-control" name="name" required></div>
            <div class="col-md-4"><label class="form-label">Photos per month</label><input class="form-control" type="number" min="1" name="monthly_photo_limit" required></div>
            <div class="col-md-2"><label class="form-label">Monthly price</label><input class="form-control" type="number" min="0" step=".01" name="price" required></div>
            <div class="col-md-2"><label class="form-label">Yearly price</label><input class="form-control" type="number" min="0" step=".01" name="yearly_price" required></div>
            <div class="col-md-2"><label class="form-label">Default billing cycle</label><select class="form-select" name="billing_cycle" required><option value="monthly">Monthly</option><option value="yearly">Yearly</option></select></div>
            <div class="col-12"><label class="form-label">Description / features</label><textarea class="form-control" name="description" rows="3" maxlength="5000"></textarea></div>
            <div class="col-12"><div class="form-check"><input class="form-check-input" type="checkbox" name="state" value="1" id="plan-state" checked><label class="form-check-label" for="plan-state">Active</label></div></div>
            <div class="col-12"><button class="btn btn-primary">Save Plan</button><button type="button" class="btn btn-outline-secondary ms-2 hide-plan-form">Cancel</button></div>
        </form>
    </div></div>
    <div class="card"><div class="card-body"><div class="table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>Plan</th><th>Description</th><th>Photos / Month</th><th>Monthly Price</th><th>Yearly Price</th><th>Default Billing Cycle</th><th>Purchased Users</th><th>Status</th><th>Actions</th></tr></thead><tbody>
        @forelse($organizationPlans as $plan)<tr><td>{{ $plan->name }}</td><td>{!! nl2br(e($plan->description)) !!}</td><td>{{ $plan->monthly_photo_limit }}</td><td>₹{{ $plan->price }}</td><td>₹{{ $plan->yearly_price }}</td><td>{{ ucfirst($plan->billing_cycle ?? 'monthly') }}</td><td><a class="btn btn-sm btn-info" href="{{ route('admin.billing.orders', ['plan' => $plan->id]) }}">{{ $plan->purchasers_count }}</a></td><td><form method="post" action="{{ route('admin.billing.organization.state', [$plan, $plan->state ? 0 : 1]) }}">@csrf<button class="btn btn-sm {{ $plan->state ? 'btn-success' : 'btn-secondary' }}">{{ $plan->state ? 'Active' : 'Inactive' }}</button></form></td><td><button type="button" class="btn btn-sm btn-warning" data-bs-toggle="collapse" data-bs-target="#edit-plan-{{ $plan->id }}">Edit</button></td></tr>
        <tr class="collapse" id="edit-plan-{{ $plan->id }}"><td colspan="8"><form class="row g-2" method="post" action="{{ route('admin.billing.plans.update', $plan) }}">@csrf @method('PUT')<input type="hidden" name="state" value="{{ $plan->state ? 1 : 0 }}"><div class="col-md-3"><input class="form-control" name="name" value="{{ $plan->name }}" required></div><div class="col-md-2"><input class="form-control" type="number" name="monthly_photo_limit" value="{{ $plan->monthly_photo_limit }}" required></div><div class="col-md-2"><input class="form-control" type="number" step=".01" name="price" value="{{ $plan->price }}" required></div><div class="col-md-2"><input class="form-control" type="number" step=".01" name="yearly_price" value="{{ $plan->yearly_price }}" required></div><div class="col-md-2"><select class="form-select" name="billing_cycle" required><option value="monthly" @selected($plan->billing_cycle === 'monthly')>Monthly</option><option value="yearly" @selected($plan->billing_cycle === 'yearly')>Yearly</option></select></div><div class="col-md-1"><button class="btn btn-primary">Update Plan</button></div><div class="col-12"><textarea class="form-control" name="description" rows="2">{{ $plan->description }}</textarea></div></form></td></tr>
        @empty<tr><td colspan="8">No plans found.</td></tr>@endforelse
    </tbody></table></div></div></div>
</div>
<script>document.getElementById('show-plan-form').onclick=()=>document.getElementById('plan-form-card').classList.remove('d-none');document.querySelectorAll('.hide-plan-form').forEach(button=>button.onclick=()=>document.getElementById('plan-form-card').classList.add('d-none'));</script>
@endsection
