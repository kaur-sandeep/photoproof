@extends('user.layouts.master')

@section('content')
<style>
    .organization-thank-you { min-height:72vh; padding:155px 16px 80px; background:linear-gradient(180deg, #f3f8ff 0, #fff 72%); }
    .organization-thank-you-card { max-width:680px; margin:0 auto; padding:48px 42px; border:1px solid #dce8f6; border-radius:20px; background:#fff; box-shadow:0 18px 52px rgba(28, 73, 132, .12); text-align:center; }
    .organization-thank-you-icon { display:grid; width:66px; height:66px; margin:0 auto 22px; place-items:center; border-radius:50%; background:#eaf8f0; color:#23935c; font-size:2rem; }
    .organization-thank-you-card h1 { margin:0 0 14px; color:#14213d; font-family:Montserrat, sans-serif; font-size:2rem; font-weight:700; }
    .organization-thank-you-card p { max-width:530px; margin:0 auto; color:#53657d; font-size:1rem; line-height:1.75; }
    .organization-thank-you-card .organization-thank-you-note { margin-top:18px; color:#72829a; font-size:.88rem; }
    .organization-thank-you-card a { display:inline-flex; margin-top:28px; padding:12px 20px; border-radius:9px; background:#1769e0; color:#fff; font-size:.88rem; font-weight:700; text-decoration:none; transition:background .2s ease; }
    .organization-thank-you-card a:hover { background:#105ecf; color:#fff; }
    @media (max-width:575px) { .organization-thank-you { padding:115px 14px 55px; } .organization-thank-you-card { padding:36px 22px; } .organization-thank-you-card h1 { font-size:1.7rem; } }
</style>
<section class="breadcrumb">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
					    <h2>Thank You!</h2>
						 <nav aria-label="breadcrumb">
							<ol class="breadcrumb-list">
								<li><a href="/">Home</a></li>
								<li>Thank You!</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		 </section>    
<section class="organization-thank-you">
    <div class="organization-thank-you-card">
        <div class="organization-thank-you-icon"><i class="bi bi-check2"></i></div>
       <h1>Thank You! Your Registration Has Been Submitted</h1>
        <p>
            Your organization registration request has been submitted successfully and is currently under review.
            Our administrator will review your request and contact you shortly once your organization has been approved.
        </p>
        <p class="organization-thank-you-note">Thank you for choosing PhotoProof.</p>
        <a href="{{ url('/') }}">Return to Home</a>
    </div>
</section>
@endsection
