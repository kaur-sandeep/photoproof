@extends('admin.layouts.master')
@section('title', 'Plan Management')
@section('content')
<div class="container-fluid">
    <h3>Plan Management</h3>
    <p class="text-muted">Create individual, corporate, and photo top-up products from one screen.</p>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3" method="post" action="{{ route('admin.billing.plans.store') }}">
                @csrf
                <div class="col-md-3"><label class="form-label">Plan Type</label><select class="form-select" name="type" id="plan_type"><option value="organization">Corporate subscription</option><option value="individual">Individual</option><option value="topup">Corporate photo top-up</option></select></div>
                <div class="col-md-3"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
                <div class="col-md-2 type-code"><label class="form-label">Code</label><input class="form-control" name="code"></div>
                <div class="col-md-2 type-org"><label class="form-label">Photos / month</label><input class="form-control" type="number" min="1" name="monthly_photo_limit"></div>
                <div class="col-md-2 type-individual d-none"><label class="form-label">Photos / day</label><input class="form-control" type="number" min="1" name="photo_limit"></div>
                <div class="col-md-2 type-topup d-none"><label class="form-label">Photo quantity</label><input class="form-control" type="number" min="1" name="photo_quantity"></div>
                <div class="col-md-2"><label class="form-label">Price</label><input class="form-control" type="number" min="0" step=".01" name="price" required></div>
                <div class="col-md-2 type-org"><label class="form-label">Duration days</label><input class="form-control" type="number" min="1" name="duration_days" value="30"></div>
                <div class="col-md-2 form-check mt-5"><input class="form-check-input" type="checkbox" name="state" value="1" checked> <label class="form-check-label">Active</label></div>
                <div class="col-12 type-org"><label class="form-label">Plan description / features</label><textarea class="form-control" name="description" rows="4" maxlength="5000" placeholder="Enter a short description or one feature per line."></textarea><small class="text-muted">This is displayed below the plan name on the pricing page.</small></div>
                <div class="col-md-2 mt-4"><button class="btn btn-primary">Create Plan</button></div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4"><div class="card"><div class="card-header">Individual plans</div><ul class="list-group list-group-flush">
            @forelse($individualPlans as $plan)<li class="list-group-item"><strong>{{ $plan->name }}</strong> <span class="badge {{ $plan->state ? 'bg-success' : 'bg-secondary' }}">{{ $plan->state ? 'Active' : 'Inactive' }}</span><br>{{ $plan->photo_limit }} photos/day · ₹{{ $plan->price }}<div class="mt-2"><details><summary class="btn btn-sm btn-outline-primary">Edit</summary><form class="row g-2 mt-2" method="post" action="{{ route('admin.billing.individual.update', $plan) }}">@csrf @method('PUT')<input type="hidden" name="state" value="{{ $plan->state ? 1 : 0 }}"><div class="col-12"><input class="form-control form-control-sm" name="name" value="{{ $plan->name }}" required></div><div class="col-6"><input class="form-control form-control-sm" type="number" name="photo_limit" min="1" value="{{ $plan->photo_limit }}" required></div><div class="col-6"><input class="form-control form-control-sm" type="number" name="price" min="0" step=".01" value="{{ $plan->price }}" required></div><div class="col-12"><button class="btn btn-sm btn-primary">Save</button></div></form></details><form class="d-inline" method="post" action="{{ route('admin.billing.individual.state', [$plan, $plan->state ? 0 : 1]) }}">@csrf <button class="btn btn-sm {{ $plan->state ? 'btn-outline-danger' : 'btn-outline-success' }}">{{ $plan->state ? 'Inactivate' : 'Activate' }}</button></form></div></li>
            @empty <li class="list-group-item">None</li> @endforelse
        </ul></div></div>

        <div class="col-lg-4"><div class="card"><div class="card-header">Corporate subscriptions</div><ul class="list-group list-group-flush">
            @forelse($organizationPlans as $plan)<li class="list-group-item"><strong>{{ $plan->name }}</strong> <span class="badge {{ $plan->state ? 'bg-success' : 'bg-secondary' }}">{{ $plan->state ? 'Active' : 'Inactive' }}</span><br>{{ $plan->monthly_photo_limit }} photos/month · {{ $plan->duration_days }} days · ₹{{ $plan->price }}@if(filled($plan->description))<div class="small text-muted mt-1">{!! nl2br(e($plan->description)) !!}</div>@endif<div class="mt-2"><details><summary class="btn btn-sm btn-outline-primary">Edit</summary><form class="row g-2 mt-2" method="post" action="{{ route('admin.billing.plans.update', $plan) }}">@csrf @method('PUT')<input type="hidden" name="state" value="{{ $plan->state ? 1 : 0 }}"><div class="col-6"><input class="form-control form-control-sm" name="name" value="{{ $plan->name }}" required></div><div class="col-6"><input class="form-control form-control-sm" name="code" value="{{ $plan->code }}" required></div><div class="col-6"><input class="form-control form-control-sm" type="number" name="monthly_photo_limit" min="1" value="{{ $plan->monthly_photo_limit }}" required></div><div class="col-6"><input class="form-control form-control-sm" type="number" name="price" min="0" step=".01" value="{{ $plan->price }}" required></div><div class="col-6"><input class="form-control form-control-sm" type="number" name="duration_days" min="1" value="{{ $plan->duration_days }}" required></div><div class="col-12"><label class="form-label small">Plan description / features</label><textarea class="form-control form-control-sm" name="description" rows="4" maxlength="5000">{{ $plan->description }}</textarea></div><div class="col-12"><button class="btn btn-sm btn-primary">Save</button></div></form></details><form class="d-inline" method="post" action="{{ route('admin.billing.organization.state', [$plan, $plan->state ? 0 : 1]) }}">@csrf <button class="btn btn-sm {{ $plan->state ? 'btn-outline-danger' : 'btn-outline-success' }}">{{ $plan->state ? 'Inactivate' : 'Activate' }}</button></form></div></li>
            @empty <li class="list-group-item">None</li> @endforelse
        </ul></div></div>

        <div class="col-lg-4"><div class="card"><div class="card-header">Corporate top-ups</div><ul class="list-group list-group-flush">
            @forelse($topupPlans as $plan)<li class="list-group-item"><strong>{{ $plan->name }}</strong> <span class="badge {{ $plan->state ? 'bg-success' : 'bg-secondary' }}">{{ $plan->state ? 'Active' : 'Inactive' }}</span><br>{{ $plan->photo_quantity }} photos · ₹{{ $plan->price }}<div class="mt-2"><details><summary class="btn btn-sm btn-outline-primary">Edit</summary><form class="row g-2 mt-2" method="post" action="{{ route('admin.billing.topups.update', $plan) }}">@csrf @method('PUT')<input type="hidden" name="state" value="{{ $plan->state ? 1 : 0 }}"><div class="col-6"><input class="form-control form-control-sm" name="name" value="{{ $plan->name }}" required></div><div class="col-6"><input class="form-control form-control-sm" name="code" value="{{ $plan->code }}" required></div><div class="col-6"><input class="form-control form-control-sm" type="number" name="photo_quantity" min="1" value="{{ $plan->photo_quantity }}" required></div><div class="col-6"><input class="form-control form-control-sm" type="number" name="price" min="0" step=".01" value="{{ $plan->price }}" required></div><div class="col-12"><button class="btn btn-sm btn-primary">Save</button></div></form></details><form class="d-inline" method="post" action="{{ route('admin.billing.topups.state', [$plan, $plan->state ? 0 : 1]) }}">@csrf <button class="btn btn-sm {{ $plan->state ? 'btn-outline-danger' : 'btn-outline-success' }}">{{ $plan->state ? 'Inactivate' : 'Activate' }}</button></form></div></li>
            @empty <li class="list-group-item">None</li> @endforelse
        </ul></div></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const type = document.getElementById('plan_type');
    const toggle = () => {
        const value = type.value;
        document.querySelectorAll('.type-org').forEach((element) => element.classList.toggle('d-none', value !== 'organization'));
        document.querySelectorAll('.type-individual').forEach((element) => element.classList.toggle('d-none', value !== 'individual'));
        document.querySelectorAll('.type-topup').forEach((element) => element.classList.toggle('d-none', value !== 'topup'));
        document.querySelectorAll('.type-code').forEach((element) => element.classList.toggle('d-none', value === 'individual'));
    };
    type.addEventListener('change', toggle);
    toggle();
});
</script>
@endsection
