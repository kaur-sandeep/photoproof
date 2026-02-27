@extends('admin.layouts.master')

@section('title', 'Reported Image Details')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            Reported Image Details
        </div>

        <div class="card-body">
            <h4>Name : {{ $reportdata->name }}</h4>
            <p><strong>Email:</strong> {{ $reportdata->email }}</p>
            <p><strong>Message:</strong> {{ $reportdata->message }}</p>

            <hr>

            <p><strong>IP Address:</strong> {{ $reportdata->ip_address }}</p>
            <p><strong>Browser:</strong> {{ $reportdata->browser }}</p>
            <p><strong>Platform:</strong> {{ $reportdata->platform }}</p>
            <p><strong>Device:</strong> {{ $reportdata->device }}</p>
            <p><strong>Device Type:</strong> {{ $reportdata->device_type }}</p>

            <hr>

            <p><strong>Location:</strong>
                {{ $reportdata->city }},
                {{ $reportdata->region }},
                {{ $reportdata->country }}
            </p>

            <p><strong>Timezone:</strong> {{ $reportdata->timezone }}</p>
        </div>
    </div>
</div>
@endsection
