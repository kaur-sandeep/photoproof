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
                    <h5>Total Employees </h5>
                     <h3>{{$total_employees}}</h3>
                </div>
            </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card text-bg-success">
                 <a href="{{ route('admin.photos') }}" style="text-decoration: none; color: inherit;">
                <div class="card-body">
                    <h5>Total Photos Uploaded </h5>
                  <p>120</p>
                </div>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="card text-bg-primary">
                <a href="#" style="text-decoration: none; color: inherit;">
                <div class="card-body">
                    <h5>Monthly Photo Usage</h5>
                  <p>140</p>
                </div>
            </a>
            </div>
        </div>

    </div>

</div>

@endsection
