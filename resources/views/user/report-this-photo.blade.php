@extends('user.layouts.master')
@section('content')
<section class="breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- <h2>Report This Photo</h2> -->
                    <nav aria-label="breadcrumb">
                    <ol class="breadcrumb-list">
                        <li><a href="/">Home</a></li>                        
                        <li>Report This Photo</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    </section>         
    <section id="contacts-2" class="bg-scroll  wide-80 contacts-section division">				
        <div class="container">					
            <div class="row d-flex align-items-stretch">
                <div class="col-md-12 col-lg-4 mb-3 d-flex align-items-center">
                <div class="h-100 d-flex align-items-center" style="background:rgba(0,0,0,.2)">
                     <img src="{{ asset('storage/' . $photo->photo) }}" class="img-fluid" />
                </div>                
                </div>
                <div class="col-md-12 col-lg-8 mb-3 d-flex flex-wrap">
                    <h2 class="h2-lg">Report This Photo</h2>	
                    <p>This verified photo is available for review for {{$daysAvailable}} days from capture. After that, access may expire for security and privacy protection.</p>
                    <div class="form-holder">
                        <form class="row contact-form" action="{{ route('report.submit', $photo->random_id)}}" method="POST">
                            @csrf
                            <div id="input-name" class="col-lg-4">
                                <input type="text" name="random_id" class="form-control name" value = "{{$photo->random_id}}" placeholder="ID:994265321" readonly> 
                            </div>
                            <div id="input-name" class="col-lg-4">
                                <input type="text" name="name" class="form-control name" placeholder="Your name"> 
                            </div>	
                            <div id="input-email" class="col-lg-4">
                                <input type="email" name="email" class="form-control email" placeholder="Email address"> 
                            </div>												
                            <div id="input-message" class="col-lg-12 input-message">
                                <textarea class="form-control message" name="message" rows="6" placeholder="Write your Comment here ..."></textarea>
                            </div> 
                            <div class="col-lg-12 mb-3">
                                 <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                            </div>
                            <div class="col-lg-12 m-top-15 form-btn text-left">	
                                <button type="submit" class="btn btn-lightgreen submit">Submit</button>	
                            </div>
                            <div class="col-lg-12 contact-form-msg">
                                <span class="loading"></span>
                            </div>													
                        </form>	
                    </div>	
                </div>	
                </div>	
            </div>	
        </div>
    </section>	
@endsection