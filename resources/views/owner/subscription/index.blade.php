@extends('admin.layouts.master')
@section('title', 'My Subscription')
@section('content')
<div class="container-fluid"><h3>My Subscription</h3>
@if($subscription)
@php($monthly = max(0, $subscription->monthly_photo_limit - $subscription->monthly_photo_used))
@php($topup = max(0, $subscription->topup_photo_limit - $subscription->topup_photo_used))
<div class="card"><div class="card-body"><h4>{{ $subscription->plan->name }}</h4><div class="row">
<div class="col-md-4">Start: {{ $subscription->starts_at->format('d M Y') }}</div><div class="col-md-4">Expiry: {{ $subscription->expires_at->format('d M Y') }}</div><div class="col-md-4">Monthly: {{ $subscription->monthly_photo_used }} / {{ $subscription->monthly_photo_limit }}</div>
<div class="col-md-4 mt-2">Monthly remaining: {{ $monthly }}</div><div class="col-md-4 mt-2">Top-up: {{ $subscription->topup_photo_used }} / {{ $subscription->topup_photo_limit }}</div><div class="col-md-4 mt-2">Total remaining: {{ $monthly + $topup }}</div>
</div><div class="mt-3"><a class="btn btn-primary" href="{{ route('owner.renew') }}">Renew Plan</a> <a class="btn btn-outline-primary" href="{{ route('owner.topup') }}">Top Up Photos</a></div></div></div>
@if($scheduledRenewal)
<div class="alert alert-info mt-3 mb-0"><strong>Renewal scheduled</strong> — {{ $scheduledRenewal->plan->name }} starts {{ $scheduledRenewal->starts_at->format('d M Y') }} and ends {{ $scheduledRenewal->expires_at->format('d M Y') }}.</div>
@endif
@else <div class="alert alert-warning">No active subscription. Create a renewal order to restore access.</div><a class="btn btn-primary" href="{{ route('owner.renew') }}">Choose a Plan</a>@endif
</div>
@endsection
