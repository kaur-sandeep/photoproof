@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')

<style>
    .col-lg-3.col-6 {padding-bottom: 25px};
</style>
<div class="container-fluid">

    <div class="row">

        <div class="col-lg-3 col-6">
            <div class="card text-bg-primary">
                <a href="{{ route('admin.users') }}" style="text-decoration: none; color: inherit;">
                <div class="card-body">
                    <h5>Total App Users</h5>
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

        <div class="col-lg-3 col-6">
            <div class="card text-bg-primary">
                <a href="#" style="text-decoration: none; color: inherit;">
                <div class="card-body">
                    <h5>Total iPhone Installations</h5>
                    <h3>0</h3>
                </div>
            </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card text-bg-success">
                 <a href="#" style="text-decoration: none; color: inherit;">
                <div class="card-body">
                    <h5>Total Android Installations</h5>
                    <h3>0</h3>
                </div>
                </a>
            </div>
        </div> 

        <div class="col-lg-3 col-6">
            <div class="card text-bg-success">
                 <a href="{{ route('admin.photos') }}" style="text-decoration: none; color: inherit;">
                <div class="card-body">
                    <h5>Photos Viewed</h5>
                    <h3>{{$totalViews}}</h3>
                </div>
                </a>
            </div>
        </div>



    </div>

</div>

@endsection
