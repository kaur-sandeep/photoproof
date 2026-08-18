@extends('admin.layouts.master')
@section('title', $plan->exists ? 'Edit Plan' : 'Create Plan')
@section('content')
<div class="container-fluid"><div class="admin-page-header mb-3"><h3><b>{{ $plan->exists ? 'Edit Plan' : 'Create Plan' }}</b></h3></div><div class="card"><div class="card-body">
    <form class="row g-3" method="post" action="{{ $plan->exists ? route('admin.billing.plans.update', $plan) : route('admin.billing.plans.store') }}">
        @csrf @if($plan->exists) @method('PUT') @endif
        <div class="col-md-6"><label class="form-label" for="name">Plan Name <span class="text-danger">*</span></label><input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $plan->name) }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-6"><label class="form-label" for="monthly_photo_limit">Photos per Month <span class="text-danger">*</span></label><input class="form-control @error('monthly_photo_limit') is-invalid @enderror" id="monthly_photo_limit" type="number" min="1" name="monthly_photo_limit" value="{{ old('monthly_photo_limit', $plan->monthly_photo_limit) }}" required>@error('monthly_photo_limit')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-6"><label class="form-label" for="monthly_price">Monthly Price <span class="text-danger">*</span></label><input class="form-control @error('monthly_price') is-invalid @enderror" id="monthly_price" type="number" min="0" step="0.01" name="monthly_price" value="{{ old('monthly_price', $plan->monthly_price) }}" required>@error('monthly_price')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-md-6"><label class="form-label" for="yearly_price">Yearly Price <span class="text-danger">*</span></label><input class="form-control @error('yearly_price') is-invalid @enderror" id="yearly_price" type="number" min="0" step="0.01" name="yearly_price" value="{{ old('yearly_price', $plan->yearly_price) }}" required>@error('yearly_price')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-12"><label class="form-label" for="description">Description / Features</label><textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" maxlength="5000">{{ old('description', $plan->description) }}</textarea>@error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="col-12"><div class="form-check"><input class="form-check-input @error('state') is-invalid @enderror" type="checkbox" name="state" id="state" value="1" @checked(old('state', $plan->exists ? $plan->state : true))><label class="form-check-label" for="state">Active</label>@error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror</div></div>
        <div class="col-12"><button class="btn btn-primary">{{ $plan->exists ? 'Update Plan' : 'Save Plan' }}</button><a class="btn btn-outline-secondary ms-2" href="{{ route('admin.billing.plans') }}">Cancel</a></div>
    </form>
</div></div></div>
@endsection
