@extends('admin.layouts.master')
@section('title', 'Top Up Photos')
@section('content')
<div class="container-fluid"><h3>Top Up Photos</h3><form method="post" action="{{ route('owner.topup.store') }}">@csrf <div class="card"><div class="card-body">@forelse($topups as $topup)<label class="d-block border rounded p-3 mb-2"><input type="radio" name="topup_plan_id" value="{{ $topup->id }}" required> <strong>{{ $topup->name }}</strong> — {{ $topup->photo_quantity }} photos — ₹{{ $topup->price }}</label>@empty <div class="alert alert-info">No top-up plans are currently available.</div>@endforelse <button class="btn btn-primary">Create Offline-Payment Order</button></div></div></form></div>
@endsection
