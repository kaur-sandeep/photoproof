@extends('admin.layouts.master')

@section('title', 'Users List')

@section('content')
<div class="container mt-5">
    <div class="card">

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

         @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card-header">
            <h4>Settings</h4>
        </div>
        <form action="{{route('admin.setting.update')}}" method="POST">
            @csrf
            <div class="card-body">
                  
            <div class="row mb-3">
                    <div class="col-md-4"><strong>Enable Email</strong></div>
                    <div class="col-md-8">
                        <select name="email_enabled" class="form-control">
                            <option value="1" {{ old('smtp_enabled', $settings->email_enabled ?? 0) == 1 ? 'selected' : '' }}>Enabled</option>
                            <option value="0" {{ old('smtp_enabled', $settings->email_enabled ?? 0) == 0 ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Enable SMTP</strong></div>
                    <div class="col-md-8">
                        <select name="smtp_enabled" class="form-control">
                            <option value="1" {{ old('smtp_enabled', $settings->smtp_enabled ?? 0) == 1 ? 'selected' : '' }}>Enabled</option>
                            <option value="0" {{ old('smtp_enabled', $settings->smtp_enabled ?? 0) == 0 ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>SMTP Host</strong></div>
                    <div class="col-md-8">
                        <input type="text" name="smtp_host" class="form-control" 
                               value="{{ old('smtp_host', $settings->smtp_host ?? '') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>SMTP Port</strong></div>
                    <div class="col-md-8">
                        <input type="number" name="smtp_port" class="form-control" 
                               value="{{ old('smtp_port', $settings->smtp_port ?? '') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>SMTP Username</strong></div>
                    <div class="col-md-8">
                        <input type="text" name="smtp_username" class="form-control" 
                               value="{{ old('smtp_username', $settings->smtp_username ?? '') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>SMTP Password</strong></div>
                    <div class="col-md-8">
                        <input type="password" name="smtp_password" class="form-control" 
                               value="{{ old('smtp_password', $settings->smtp_password ?? '') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>SMTP Encryption</strong></div>
                    <div class="col-md-8">
                        <select name="smtp_encryption" class="form-control">
                            <option value="tls" {{ old('smtp_encryption', $settings->smtp_encryption ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ old('smtp_encryption', $settings->smtp_encryption ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="" {{ old('smtp_encryption', $settings->smtp_encryption ?? '') == '' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Delete Photos After (Days)</strong></div>
                    <div class="col-md-8">
                        <input type="number" name="delete_photos_after_days" class="form-control" min="1"
                               value="{{ old('delete_photos_after_days', $settings->delete_photos_after_days ?? '') }}">
                    </div>
                </div>

            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>

        </form>
    </div>
</div>
@endsection
