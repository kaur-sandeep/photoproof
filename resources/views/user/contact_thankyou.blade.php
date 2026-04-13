@extends('user.layouts.master')

@section('content')

<section class="breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Thank You!</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb-list">
                        <li><a href="/">Home</a></li>
                        <li>Contact Submitted</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>         

<section class="bg-scroll wide-80 contacts-section division">				
    <div class="container">
        <div class="row">	
            <div class="col-md-10 offset-md-1 section-title text-center">

                <h2 class="h2-lg">Thank You for Contacting Us</h2>	

                <h4 class="text-white">Your message has been received successfully.</h4>

                <p>
                    Our team will review your inquiry and get back to you as soon as possible.
                </p>

                <p>
                    We appreciate your interest in <strong>Photo Proof</strong> and look forward to assisting you.
                </p>

                <div class="mt-4">
                    <a href="/" class="btn btn-lightgreen">Go Back to Home</a>
                </div>

            </div> 	
        </div>
    </div>
</section>	

@endsection