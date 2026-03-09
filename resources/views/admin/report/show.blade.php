@extends('admin.layouts.master')

@section('title', 'Reported Image Details')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <b>Reported Image Details</b>
        </div>

        <div class="card-body">
          @if(!empty($reportdata->name))
            <p><strong>Name :</strong> {{ $reportdata->name }}</p>
            @endif

            @if(!empty($reportdata->email))
            <p><strong>Email:</strong> {{ $reportdata->email }}</p>
            @endif

            @if(!empty($reportdata->message))
            <p><strong>Message:</strong> {{ $reportdata->message }}</p>
            @endif

            <hr>

            @if(!empty($reportdata->ip_address))
            <p><strong>IP Address:</strong> {{ $reportdata->ip_address }}</p>
            @endif

            @if(!empty($reportdata->browser))
            <p><strong>Browser:</strong> {{ $reportdata->browser }}</p>
            @endif

            @if(!empty($reportdata->platform))
            <p><strong>Platform:</strong> {{ $reportdata->platform }}</p>
            @endif

            @if(!empty($reportdata->device))
            <p><strong>Device:</strong> {{ $reportdata->device }}</p>
            @endif

            @if(!empty($reportdata->device_type))
            <p><strong>Device Type:</strong> {{ $reportdata->device_type }}</p>
            @endif

            <hr>
            @if(!empty($reportdata->city &&  $reportdata->region && $reportdata->country ))
            <p><strong>Location:</strong>
                {{ $reportdata->city }},
                {{ $reportdata->region }},
                {{ $reportdata->country }}
            </p>
            @endif
             @if(!empty($reportdata->timezone))
            <p><strong>Timezone:</strong> {{ $reportdata->timezone }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
