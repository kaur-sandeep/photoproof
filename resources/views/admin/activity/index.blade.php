@extends('admin.layouts.master')

@section('title', 'Activity Logs')

@section('content')

<div class="container">
    <h3 class="mb-4">Admin Activity Logs</h3>

    <div class="card">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Admin</th>
                        <th>Action</th>
                        <th>Module</th>
                        <th>Description</th>
                        <th>IP Address</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $key => $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $key }}</td>
                            <td>{{ $log->admin->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ ucfirst($log->action) }}
                                </span>
                            </td>
                            <td>{{ ucfirst($log->module) }}</td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td>{{ $log->created_at->format('d M Y h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                No activity logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $logs->links() }}
            </div>

        </div>
    </div>
</div>

@endsection