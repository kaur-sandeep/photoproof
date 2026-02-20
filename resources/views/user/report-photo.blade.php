@extends('user.layouts.master')

@section('content')
<section class="breadcrumb">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
					    <h2>Report This Photo</h2>
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
					<div class="row">	
						<div class="col-md-10 offset-md-1 section-title">
							<h2 class="h2-lg">Report This Photo</h2>	
							<p>This verified photo is available for review for 20 days from capture. After that, access may expire for security and privacy protection.</p>
						</div> 	
					</div>
				 	<div class="row">
				 		<div class="col-md-10 col-lg-8 offset-md-1 offset-lg-2">
				 			<div class="form-holder">

								<form class="row contact-form"   action="{{ route('report.submit', $photo->random_id ) }}"    method="POST">
									   @csrf
									<div id="input-name" class="col-lg-6">
										<input type="text" name="name" class="form-control name" placeholder="Your name" required> 
									</div>												
									<div id="input-email" class="col-lg-6">
										<input type="text" name="email" class="form-control email" placeholder="Email address" required> 
									</div>												
									<div id="input-message" class="col-lg-12 input-message">
										<textarea class="form-control message" name="message" rows="6" placeholder="Write your Comment here ..." required></textarea>
									</div> 
									  <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>

									<div class="col-lg-12 m-top-15 form-btn text-center">	
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
			</section>	
            
 @endsection