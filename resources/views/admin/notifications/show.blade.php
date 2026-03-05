@extends('admin.layouts.master')

@section('title', 'Notification Details')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            Notification Details
        </div>
            @php
            $data = json_decode($notification->data);
            @endphp

        <div class="card-body">
            <h4>Name : {{ $notification->name }}</h4>
            <p><strong>Email:</strong> {{ $notification->email }}</p>
            <p><strong>Message:</strong> {{ $data->message }}</p>

            <hr>

            <p><strong>IP Address:</strong> {{ $data->ip }}</p>
            <p><strong>Browser:</strong> {{ $data->browser }}</p>
            <p><strong>Platform:</strong> {{ $data->platform }}</p>
            <p><strong>Device:</strong> {{ $data->device }}</p>
            <p><strong>Device Type:</strong> {{ $data->deviceType }}</p>
            <hr>
             <p><strong>Location:</strong> {{ $data->country }} , {{ $data->region}} , {{$data->city}} , {{$data->zip}}</p>
            <p><strong>Timezone:</strong> {{ $data->timezone }}</p>
        </div>
    </div>
</div>
@endsection
