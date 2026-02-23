@extends('admin.layouts.master')

@section('title', 'Notifications List')

@section('content')
<div class="container mt-4" style="max-width: 750px;">
    <h4 class="mb-4 fw-bold">Notifications</h4>

    @forelse($notifications as $notification)
        <div class="notification-item d-flex flex-column p-3 mb-3 rounded shadow-sm 
            {{ $notification->is_read ? 'bg-white' : 'bg-light border-start border-4 border-primary' }}"
            data-id="{{ $notification->id }}"
            data-is-read="{{ $notification->is_read ? 1 : 0 }}"
            style="cursor: pointer;">
            
            <!-- Email -->
            <div class="mb-1">
                <strong>Email:</strong> {{ $notification->email }}
            </div>

            <!-- IP Address -->
            <div class="mb-1">
                <strong>IP Address:</strong> {{ $notification->ip_address ?? 'N/A' }}
            </div>

            <!-- Message -->
            <div class="mb-1">
                <strong>Message:</strong> 
                <span class="notification-message fw-semibold text-dark">
                    {{ \Illuminate\Support\Str::limit($notification->message, 60) }}
                </span>
            </div>

            <!-- Time -->
            <div>
                <small class="text-muted">
                    {{ \App\Helpers\DateTime::dateFormat($notification->created_at) }}
                </small>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-5">
            No notifications available
        </div>
    @endforelse

    <div class="d-flex justify-content-center mt-4">
        <!-- Pagination (if needed) -->
        {{ $notifications->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection