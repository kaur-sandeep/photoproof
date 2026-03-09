@extends('admin.layouts.master')

@section('title', 'Notification Details')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <b>Notification Details</b>
        </div>
            @php
            $data = json_decode($notification->data);
            @endphp

        <div class="card-body">
          @if(!empty($notification->name))
            <p><strong>Name :</strong> {{ $notification->name }}</p>
            @endif

            @if(!empty($notification->email))
            <p><strong>Email:</strong> {{ $notification->email }}</p>
            @endif

            @if(!empty($data->message))
            <p><strong>Message:</strong> {{ $data->message }}</p>
            @endif

            <hr>

            @if(!empty($data->ip))
            <p><strong>IP Address:</strong> {{ $data->ip }}</p>
            @endif

            @if(!empty($data->browser))
            <p><strong>Browser:</strong> {{ $data->browser }}</p>
            @endif

            @if(!empty($data->platform))
            <p><strong>Platform:</strong> {{ $data->platform }}</p>
            @endif

            @if(!empty($data->deviceType))
            <p><strong>Device Type:</strong> {{ $data->deviceType }}</p>
            @endif
            <hr>
                @if(!empty($data->city &&  $data->region && $data->country ))
             <p><strong>Location:</strong> {{ $data->country }} , {{ $data->region}} , {{$data->city}} , {{$data->zip}}</p>@endif
              @if(!empty($data->timezone ))
            <p><strong>Timezone:</strong> {{ $data->timezone }}</p>@endif
        </div>
    </div>
</div>
@endsection
