@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    <div class="row">

        <div class="col-lg-3 col-6">
            <div class="card text-bg-primary">
                <div class="card-body">
                    <h5>Total Users</h5>
                    <h3>{{$totalUsers}}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card text-bg-success">
                <div class="card-body">
                    <h5>Total Photos</h5>
                    <h3>{{$totalPhotos}}</h3>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
