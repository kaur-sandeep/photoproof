@extends('admin.layouts.master')

@section('title', 'Users List')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{$user->email}}</h3>
        </div>
        <div class="card-body">
            <table id="photodataTableList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Images</th>
                        <th>Viewed/Verified</th>
                        <th>Track Details</th>
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
