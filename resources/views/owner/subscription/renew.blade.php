@extends('admin.layouts.master')
@section('title', 'Renew Plan')
@section('content')
<div class="container-fluid"><h3>Renew Plan</h3><form method="post" action="{{ route('owner.renew.store') }}">@csrf <div class="card"><div class="card-body">@foreach($plans as $plan)<label class="d-block border rounded p-3 mb-2"><input type="radio" name="subscription_plan_id" value="{{ $plan->id }}" required> <strong>{{ $plan->name }}</strong> — {{ $plan->monthly_photo_limit }} photos / {{ $plan->duration_days }} days — ₹{{ $plan->price }}</label>@endforeach @error('subscription_plan_id')<div class="text-danger">{{ $message }}</div>@enderror <button class="btn btn-primary">Create Offline-Payment Order</button></div></div></form></div>
@endsection
