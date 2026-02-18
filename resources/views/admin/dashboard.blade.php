@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    <div class="row">

        <div class="col-lg-3 col-6">
            <div class="card text-bg-primary">
                <a href="{{ route('admin.users') }}" style="text-decoration: none; color: inherit;">
                <div class="card-body">
                    <h5>Total Users</h5>
                    <h3>{{$totalUsers}}</h3>
                </div>
            </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card text-bg-success">
                 <a href="{{ route('admin.photos') }}" style="text-decoration: none; color: inherit;">
                <div class="card-body">
                    <h5>Total Photos</h5>
                    <h3>{{$totalPhotos}}</h3>
                </div>
                </a>
            </div>
        </div>

    </div>

</div>

@endsection
