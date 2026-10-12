@extends('user.layouts.master')

@section('content')
<section class="hero-section division" style="min-height: 70vh; display: flex; align-items: center; padding: 150px 0 90px;">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <p class="text-uppercase mb-2" style="color: #b7f36b; font-weight: 700; letter-spacing: .16em;">Page not found</p>
                <h1 style="font-size: clamp(96px, 18vw, 180px); line-height: .95; font-weight: 800; color: #fff; margin: 0;">404</h1>
                <h2 class="h2-lg text-white mt-4">We can’t find that page</h2>
                <p class="p-lg text-white mt-3" style="opacity: .85;">The address may be incorrect, or the page may have moved. Head back to Photoproof and continue from there.</p>
                <a href="{{ url('/') }}" class="btn btn-lightgreen submit mt-4">Back to home</a>
            </div>
        </div>
    </div>
</section>
@endsection
