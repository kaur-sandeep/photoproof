@extends('admin.layouts.master')

@section('title', 'Photos List')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            Notification Details
        </div>

        <div class="card-body">
            <h4>{{ $notification->name }}</h4>
            <p><strong>Email:</strong> {{ $notification->email }}</p>
            <p><strong>Message:</strong> {{ $notification->message }}</p>

            <hr>

            <p><strong>IP Address:</strong> {{ $notification->ip_address }}</p>
            <p><strong>Browser:</strong> {{ $notification->browser }}</p>
            <p><strong>Platform:</strong> {{ $notification->platform }}</p>
            <p><strong>Device:</strong> {{ $notification->device }}</p>
            <p><strong>Device Type:</strong> {{ $notification->device_type }}</p>

            <hr>

            <p><strong>Location:</strong>
                {{ $notification->city }},
                {{ $notification->region }},
                {{ $notification->country }}
            </p>

            <p><strong>Timezone:</strong> {{ $notification->timezone }}</p>
        </div>
    </div>
</div>
@endsection
