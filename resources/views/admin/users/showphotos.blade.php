@extends('admin.layouts.master')

@section('title', 'Users List')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="d-flex align-items-center gap-3 mb-0">
                 <?php $default = "https://cdn-icons-png.flaticon.com/512/149/149071.png";?>
                <img src="{{ $user->profile_image 
        ? asset('storage/profile/' . $user->profile_image) 
        :  $default }}"
                    width="45"
                    height="45"
                    class="rounded-circle shadow-sm">
                <div>
                    <div class="fw-semibold">{{ $user->name }}</div>
                    <small class="text-muted">{{ $user->email }}</small>
                </div>
            </h5>
        </div>
        <div class="card-body">
            <table id="photodataTableList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Images</th>
                        <th>Random Id</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Viewed/Verified</th>
                        <th>Track Details</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    window.USER_ID = {{ $user->id }};
</script>

@endsection
