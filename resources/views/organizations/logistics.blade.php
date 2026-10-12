@extends('user.layouts.master')

@section('content')
<style>
    /* ---------- Theme tokens (match hero + feature cards) ---------- */
    .organization-page {
        --org-bg-top:#030c26; --org-bg-bottom:#0e1836;
        --org-green:#1fda69; --org-green-dark:#16b456;
        --org-surface:rgba(255,255,255,.04); --org-surface-strong:rgba(255,255,255,.07);
        --org-line:rgba(255,255,255,.10); --org-line-strong:rgba(255,255,255,.18);
        --org-text:#ffffff; --org-text-soft:#c5cee0; --org-text-muted:#8f9bb5;
        padding:20px;
		border-radius:20px;
        /*background:linear-gradient(to bottom, var(--org-bg-top) 0%, var(--org-bg-bottom) 100%);*/
		background:rgba(3,12,38,.65);
        color:var(--org-text);
        overflow:hidden;
	 border: 1px solid var(--org-line-strong);
	    /* border: 1px solid var(--org-line);*/
    }
    .organization-page * { box-sizing:border-box; }
    .organization-shell { max-width:1180px; margin:0 auto; }

    /* ---------- Section heading (same pattern as "How Photo Proof works") ---------- */
    .organization-heading { text-align:left;  margin:0 auto 0px; }
   
    .organization-heading p { color:var(--org-text-soft); margin:0; text-align:left; font-size:15px; }

    /* ---------- Full-width form layout ---------- */
    .organization-onboarding { width:100%; }

    /* Form card */
    .organization-card { width:100%; margin: 0 auto; /*padding:40px 44px; border:1px solid var(--org-line); border-radius:20px; background:var(--org-surface); box-shadow:0 24px 60px rgba(0,0,0,.35); backdrop-filter:blur(6px);*/ }
    .organization-card-header { display:flex; gap:14px; align-items:center; margin-bottom:28px; padding-bottom:22px; border-bottom:1px solid var(--org-line); }
    .organization-card-icon { display:grid; flex:0 0 46px; width:46px; height:46px; place-items:center; border-radius:12px; background:var(--org-green); color:#05210f; font-size:1.3rem; box-shadow:0 8px 20px rgba(31,218,105,.28); }
    .organization-card-header h2 { margin:0 0 3px; color:#fff; font-family:Montserrat, sans-serif; font-size:1.3rem; font-weight:700; }
    .organization-card-header p { margin:0; color:var(--org-text-muted); font-size:.9rem; line-height:1.5; }

    /* Alerts */
    .organization-alert { display:flex; gap:10px; align-items:center; margin:0 0 22px; padding:13px 14px; border-radius:10px; font-size:.88rem; line-height:1.45; }
    .organization-alert i { margin-top:2px; font-size:1rem; }
    .organization-alert-success { border:1px solid rgba(31,218,105,.35); background:rgba(31,218,105,.10); color:#8ff0b5; }
    .organization-alert-danger { border:1px solid rgba(255,107,107,.35); background:rgba(255,107,107,.10); color:#ffb3b3; }
    .organization-alert ul { margin:0; padding-left:0px; }

    /* Plan summary */
    .organization-plan-summary { display:flex; align-items:center; margin:10px 0px ; padding:0px; border-radius:12px; background:#00a6ff; }
    .organization-plan-summary i { display:grid; flex:0 0 36px; width:36px; height:36px; place-items:center; border-radius:12px 0px 0px 12px; #fff font-size:1rem; }
    .organization-plan-summary small { display:block; color:var(--org-text-muted); font-size:.78rem; font-weight:600; }
    .organization-plan-summary strong { color:#fff; font-size:.92rem; font-weight:600; }

    /* Sections */
    .organization-form-section + .organization-form-section { margin-top:24px; padding-top:24px; border-top:1px solid var(--org-line); }
    .organization-section-title { display:flex; align-items:center; gap:8px; margin:0 0 16px; color:#fff; font-size:.95rem; font-weight:700; }
    .organization-section-title i { color:var(--org-green); font-size:1rem; }

    /* Fields */
    .organization-form-grid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; }
    .organization-form-group { min-width:0; }
    .organization-form-group.full { grid-column:1 / -1; }
    .organization-form-group label { display:block; margin:0 0 7px; color:var(--org-text-soft); font-size:.82rem; font-weight:normal; text-align:left; }
	.organization-form-group label.organization-terms { display: flex; align-items: center; padding-bottom:10px;}
    .organization-required { color:#ff7b7b; }
    .organization-form-group input:not([type="checkbox"]),
    .organization-form-group textarea {
        width:100%; min-height:48px; padding:12px 14px;
        border:1px solid var(--org-line-strong); border-radius:10px;
        /*background:var(--org-surface-strong);*/
    background: rgb(25 28 43);

		color:#fff;
        font:400 .9rem Roboto, sans-serif; outline:0;
        transition:border-color .18s, box-shadow .18s, background .18s;
    }
    .organization-form-group textarea { min-height:100px; resize:vertical; }
    .organization-form-group input::placeholder, .organization-form-group textarea::placeholder { color:#fff; }
    .organization-form-group input:not([type="checkbox"]):focus,
    .organization-form-group textarea:focus { border-color:var(--org-green); background:rgba(255,255,255,.09); box-shadow:0 0 0 3px rgba(31,218,105,.18); }
    .organization-form-group input:-webkit-autofill { -webkit-text-fill-color:#fff; -webkit-box-shadow:0 0 0 1000px #17223f inset; caret-color:#fff; }

    .organization-captcha {  padding:2px; }

    /* Terms */
    .organization-terms { display:flex; align-items:flex-start; gap:10px; margin:0; color:var(--org-text-soft); font-size:.86rem; line-height:1.5; cursor:pointer; }
    .organization-terms input { flex:0 0 18px; width:18px; height:18px; margin:0px 0 0; accent-color:var(--org-green); cursor:pointer; }
    .organization-terms a { color:var(--org-green); font-weight:600; text-decoration:none; }
    .organization-terms a:hover { text-decoration:underline; }

    /* Actions */
    .organization-actions { display:flex; justify-content:flex-start; margin-top:0px; padding-top:0px; border-top:0px solid var(--org-line); }
    .organization-button-submit {
        display:inline-flex; min-height:50px; align-items:center; justify-content:center; gap:8px;
        padding:12px 26px; border:0; border-radius:10px;
        background:var(--org-green); color:#05210f;
        font-size:.95rem; font-weight:700;
        box-shadow:0 10px 24px rgba(31,218,105,.25);
        transition:background .2s, transform .2s, box-shadow .2s;
    }
    .organization-button-submit:hover { background:var(--org-green-dark); color:#05210f; transform:translateY(-1px); box-shadow:0 12px 28px rgba(31,218,105,.32); }
    .organization-button-submit:focus-visible { outline:3px solid rgba(31,218,105,.5); outline-offset:3px; }

    /* Modal */
    #organizationTermsModal .modal-content { border:0; border-radius:16px; overflow:hidden; }
    #organizationTermsModal .modal-header { background:var(--org-bg-bottom, #0e1836); color:#fff; border-bottom:0; }
    #organizationTermsModal .modal-header .btn-close { filter:invert(1); }
    #organizationTermsModal .modal-body {     color: #fff;
    background: linear-gradient(to bottom, #030c26 0%, #0e1836 100%); }
    #organizationTermsModal .modal-body h6 { margin-top:16px; color:#fff; font-weight:700; }
	#organizationTermsModal .modal-body p {
		font-size: 16px;
	}
	#organizationTermsModal .modal-footer {
		background: #0e1836;
		border-top: 1px solid #444a5a;
	}
    #organizationTermsModal .modal-body h6:first-child { margin-top:0; }
    #organizationTermsModal .btn-agree { background:#1fda69; border-color:#1fda69; color:#05210f; font-weight:700; }
    #organizationTermsModal .btn-agree:hover { background:#16b456; border-color:#16b456; }
.innerbanner.hero-banner{background-image:url(../user/images/256-1.jpg);}
#create-account h2.h2-xs {
    font-size: 1.50rem;
    text-align: left;
	margin:0px; padding:0px;
}
.hero-row-200 {padding-bottom:30px;}
/*.innerbanner.hero-banner::before {    background: linear-gradient(52deg, #000000eb, #00000000);}*/
.innerbanner.hero-banner::before {    background: rgba(0,0,0,.6);}

.organization-features{display:grid; margin-top:25px;    grid-template-columns: 1fr 1fr ; gap:10px;}

.organization-feature {display:flex; margin-bottom:0px; padding-bottom:15px; }
.organization-feature p{display:none;}
.hero-txt h2 {margin-bottom:0px; padding:0px 0px 00px 0px; font-size:15px !important; font-weight:normal; color:#fff !important;}
.organization-feature-icon i {   background:#1fda69; border-radius:5px; float:left;
    color: #05210f; padding:5px; margin-right:10px;}
.logo_banner {padding-bottom:15px; }
.logo_banner img {max-width:100%;}
.hero-banner p{font-size:16px;}
.modal.show .modal-dialog{z-index:9;}
.h1, h1{font-size:3.0rem;}
.btn-lightgreen{background:#040d27; border-color:#040d27; color:#fff !important;}
.feature-icon{margin:0px 10px 0px 0px;    width: 35px;
    height: 35px;
    border-radius: 5px;}
	.feature-icon svg{max-width:25px !important; height:auto !important;}
.feature-title{margin:0px; font-weight:normal;}
.feature-desc{padding-top:10px;}
.feature-icon-box{display:flex; align-items:center;}
.feature-card{padding:15px;}

    @media (prefers-reduced-motion: reduce) { .organization-button-submit { transition:none; } .organization-button-submit:hover { transform:none; } }
    @media (max-width:991px) {
		.hero-row-200 .col-lg-5{order:2;}
		.hero-row-200 .col-lg-7{order:1; margin-bottom:15px;}
		
		
        .organization-card { padding:8px 1px; }
		.hero-txt.white-color {
			text-align: left;
		}
		.hero-txt h2 {
			text-align: left;
		}
		.organization-feature-icon i {
			margin-right: 10px;
			width: 31px;
			text-align: center;
		}
		.organization-feature {
			align-items: center;
		}
		.organization-feature div {
			flex: 1;
		}
		h1.h2-xs {
			font-size: 2.1rem;
		}
		.row.howsetupbx {
			margin: 0;
		}
		.howsetupbx .col-sm-6.col-lg-4 .feature-icon-box {
			flex-direction: column;
		}
		.howsetupbx .col-sm-6.col-lg-4 .feature-icon-box .feature-icon {
			margin-bottom: 10px;
		}
		.fraud-highlight {
				margin-bottom: 21px;
		}
		.fraud-item {
			 font-size: 16px;
		}
		.howsetupbx .col-sm-6.col-lg-4 .feature-desc {
			font-size: 16px;
			text-align: center;
			padding-top: 0;
		}
		.howsetupbx .col-sm-6.col-lg-4 {
			max-width: 33.333%;
			flex: 0 0 33%;
			padding: 6px;
		}
    }
    @media (max-width:575px) {
        .organization-page { padding:26px 12px 20px; }
         .organization-heading {
				margin-bottom: 16px;
		}
		.organization-heading p {
			margin-top: 10px;
		}
		.organization-card {
			padding: 1px 2px;
			border-radius: 16px;
		}
        .organization-form-grid { grid-template-columns:1fr; }
      
        .organization-card-header { align-items:flex-start; }
        .organization-button-submit { width:100%; }
		 .hero-row-100, .hero-row-120, .hero-row-140, .hero-row-160, .hero-row-180, .hero-row-200 {
			padding-top: 49px;
		}
		.hero-txt h2 {
			font-size: 12px !important;
		}
		.organization-actions p {
			font-size: 14px;
		}
		.organization-actions {
			flex-direction: column;
			gap: 10px;
		}
		.howsetupbx .col-sm-6.col-lg-4 {
			max-width: 100%;
			flex: 0 0 100%;
			padding: 6px;
		}
		 .wide-100 {
			padding-top: 40px;
			padding-bottom: 40px;
		}
		.second-row.wide-50.division {
			padding-top: 30px;
		}
		.feature-title {
			font-size: 18px;
			font-weight: 700;
			margin-bottom: 8px;
		}
		.fraud-col {
			padding: 21px 21px;
			border-radius: 20px;
			height: 100%;
		}
		.fraud-highlight p {
			font-size: 14px !important;
		}

    }
</style>

<section class="bg-scroll bg-purple hero-section division hero-banner innerbanner">
    <div class="container">
        <div class="row hero-row-200">
            <div class="col-md-12 col-lg-5 d-flex align-items-center">
                <div class="hero-txt white-color">
               
					
					<h1 class="h2-xs animated fadeInRight visible" data-animation="fadeInRight" data-animation-delay="300">
                         Smart Delivery Verification for <span class="text-white">Modern Logistics Fleets</span>
                    </h1>
                    <p class="p-lg animated fadeInUp visible" data-animation="fadeInUp" data-animation-delay="400">
                       PhotoProof locks delivery photo capture to real-time GPS and server-synced timestamps. No gallery uploads, no spoofed locations—just tamper-proof visual proof for every consignment.
                    </p>
					
					<div class="organization-features">
                    <article class="organization-feature"><span class="organization-feature-icon"><i class="bi bi-camera"></i></span><div><h2>Live POD Capture & Asset Management</h2><p>Capture photos in real time and keep everything organized in one place.</p></div></article>
                    <article class="organization-feature"><span class="organization-feature-icon"><i class="bi bi-geo-alt"></i></span><div><h2>Geofence & Location-Verified Drop-Offs</h2><p>Keep important location information connected with every photo.</p></div></article>
                    <article class="organization-feature"><span class="organization-feature-icon"><i class="bi bi-people"></i></span><div><h2>Fleet & Dispatch Collaboration</h2><p>Invite your team, assign roles and track photo uploads effortlessly.</p></div></article>
                    <article class="organization-feature"><span class="organization-feature-icon"><i class="bi bi-lock"></i></span><div><h2>Secure, Audit-Ready Chain of Custody</h2><p>Keep organizational data secure and accessible only to authorized users.</p></div></article>
                </div>
					
                </div>
            </div>
            <div class="col-md-12 col-lg-7 text-right">
               <!-- <img src="https://photoproof.cogniter.com/user/images/log1.png" class="img-fluid heroimg" id="" alt="Photo Proof app preview"> -->
			   
			   <section class="organization-page" id="create-account">
    <div class="organization-shell">

        <div class="organization-heading">
            <h2 class="h2-xs animated fadeInUp visible" data-animation="fadeInUp" data-animation-delay="300">Create Account for Your <span class="text-white">Logistics Company</span></h2>
            <p>It takes a couple of minutes. Your team can start capturing photos as soon as you're set up.</p>
        </div>

        <div class="organization-onboarding">

            {{-- Form card --}}
            <div class="organization-card">
                <!--<div class="organization-card-header">
                    <span class="organization-card-icon"><i class="bi bi-buildings"></i></span>
                    <div>
                        <h2>Corporate account details</h2>
                        <p>Fill in the details below to register your company with Photo Proof.</p>
                    </div>
                </div> -->

                @if(session('success'))
                    <div id="flash-message" class="organization-alert organization-alert-success" role="status"><i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span></div>
                @endif

                @if($errors->any())
                    <div id="flash-message" class="organization-alert organization-alert-danger" role="alert"><i class="bi bi-exclamation-triangle-fill"></i><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif

                <form method="POST" action="{{ route('organization.store-v3') }}">
                    @csrf

                    @if($selectedPlan)
                        <input type="hidden" name="subscription_plan" value="{{ $selectedPlan->id }}">
                        <input type="hidden" name="billing_cycle" value="{{ $billingCycle }}">
                        <div class="organization-plan-summary">
                            <i class="bi bi-patch-check-fill"></i>
                            <div>
                               <!-- <small>Selected plan</small> -->
                                <strong>{{ $selectedPlan->name }} &nbsp;|&nbsp; ${{ number_format($billingCycle === 'yearly' ? $selectedPlan->yearly_price : $selectedPlan->monthly_price, 2) }} / {{ $billingCycle === 'yearly' ? 'year' : 'month' }} &nbsp;|&nbsp; {{ number_format($selectedPlan->monthly_photo_limit) }} photos / month</strong>
                            </div>
                        </div>
                    @endif

                    <div class="organization-form-section">
                        <!-- <h3 class="organization-section-title"><i class="bi bi-building"></i> Organization information</h3> -->
                        <div class="organization-form-grid">
                            <div class="organization-form-group full">
                                <label for="organization_name">Organization Name <span class="organization-required">*</span></label>
                                <input id="organization_name" type="text" name="organization_name" placeholder="Enter organization name" value="{{ old('organization_name') }}" required>
                            </div>
                            <div class="organization-form-group">
                                <label for="business_type">Business Type</label>
                                <!-- <input id="business_type" type="text" name="business_type" placeholder="e.g. Delivery, logistics" value="{{ old('business_type') }}"> -->
                                <input id="business_type" type="text" name="business_type" readonly placeholder="Logistics" value="Logistics">
                            </div>
							<div class="organization-form-group">
                                <label for="owner_name">Name</label>
                                <input id="owner_name" type="text" name="owner_name" placeholder="Name" value="{{ old('owner_name') }}" autocomplete="name">
                            </div>
                            <div class="organization-form-group">
                                <label for="mobile_number">Mobile Number</label>
                                <input id="mobile_number" type="tel" name="mobile_number" placeholder="Mobile Number" value="{{ old('mobile_number') }}" autocomplete="tel">
                            </div>
                            <div class="organization-form-group ">
                                <label for="organization_email">Email Address <span class="organization-required">*</span></label>
                                <input id="organization_email" type="email" name="organization_email" placeholder="Email Address" value="{{ old('organization_email') }}" autocomplete="email" required>
                            </div>
							<div class="organization-form-group">
                                <label for="password">Password <span class="organization-required">*</span></label>
                                <input id="password" type="password" name="password" placeholder="Enter password" autocomplete="new-password" required>
                            </div>
                            <div class="organization-form-group">
                                <label for="password_confirmation">Confirm Password <span class="organization-required">*</span></label>
                                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm password" autocomplete="new-password" required>
                            </div>
							<!--<div class="organization-form-group full">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" placeholder="Tell us anything that will help us support your team" rows="4">{{ old('message') }}</textarea>
                            </div> -->
                            <div class="organization-form-group full organization-captcha ">
                               <!-- <label>Security verification <span class="organization-required">*</span></label> -->
                                <!-- <div class="g-recaptcha" data-theme="dark" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div> -->
                                 <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha_v3.site_key') }}"></script>

                                <script>
                                grecaptcha.ready(function () {
                                    grecaptcha.execute(
                                        '{{ config('services.recaptcha_v3.site_key') }}',
                                        { action: 'organization_registration' }
                                    ).then(function (token) {
                                        document.getElementById('recaptcha_token').value = token;
                                    });
                                });
                                </script>

                                <input type="hidden" name="recaptcha_token" id="recaptcha_token">

                            </div>
                            <div class="organization-form-group full">
                                <label class="organization-terms" for="terms">
                                    <input type="checkbox" id="terms" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                                    <span>I agree to the
                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#organizationTermsModal">Corporate Account Terms &amp; Conditions</a>
                                        <span class="organization-required">*</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                  

                    <input type="hidden" name="initial_landing_page" id="initial_landing_page">
                    <input type="hidden" name="submitted_from" id="submitted_from">

                    <div class="organization-actions">
                        <button type="submit" class="organization-button-submit" style="white-space:nowrap;">Create corporate account <i class="bi bi-arrow-right"></i></button>
						<p style="text-align: left; margin: 0px; padding-left: 10px;">Need more than 50 photos a month, or a custom setup? <a href="https://photoproof.cogniter.com/#contact-us" style="color:#1fda69;" target="_blank">Talk to our team →</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

   
</section>
			   
			   
			   
            </div>
        </div>
    </div>
</section>




<div class="third-row wide-60 division" style="padding-top:50px; padding-bottom:0px;"> 
    <div class="container">
        <div class="text-center">
            <h2 class="h2-xs animated w-100 text-center mb-2 fadeInLeft visible" data-animation="fadeInLeft" data-animation-delay="300"><span class="text-white">How</span> Photo Proof <span class="text-white">works</span></h2>
            <p class="mb-5">Set up your company and start building a clearer record of work in the field.</p>
        </div>
        <div class="row howsetupbx">
            <div class="col-sm-6 col-lg-4 reveal reveal-delay-1 visible mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                <div class="feature-card">
                    <div class="feature-icon-box">
					<div class="feature-icon">
                        <svg width="35" height="35" viewBox="0 0 24 24" fill="currentColor" style="color:#1fda69" aria-hidden="true">
						  <path fill-rule="evenodd" d="M3 2h8a1 1 0 0 1 1 1v19H9v-3H6v3H2V3a1 1 0 0 1 1-1Zm1.5 3.5v2h2v-2h-2Zm3.5 0v2h2v-2H8Zm-3.5 4v2h2v-2h-2Zm3.5 0v2h2v-2H8Zm-3.5 4v2h2v-2h-2Zm3.5 0v2h2v-2H8Z"/>
						  <path fill-rule="evenodd" d="M18 12.5a4.75 4.75 0 1 1 0 9.5 4.75 4.75 0 0 1 0-9.5Zm-.75 2.25v1.75H15.5v1.5h1.75v1.75h1.5v-1.75h1.75v-1.5h-1.75v-1.75h-1.5Z"/>
						</svg>
                    </div>
                    <div class="feature-title">Create your company</div>
					</div>
                    <div class="feature-desc">Register your company and contact details.</div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 reveal reveal-delay-2 visible mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                <div class="feature-card">
                    
					<div class="feature-icon-box"><div class="feature-icon">
                        <svg width="35" height="35" viewBox="0 0 24 24" fill="currentColor" style="color:#1fda69" aria-hidden="true">
						  <circle cx="9" cy="7.75" r="3.75"/>
						  <path d="M2 21.5a7 7 0 0 1 14 0V22H2v-.5Z"/>
						  <path d="M18.75 6.5h1.5v2.75H23v1.5h-2.75v2.75h-1.5v-2.75H16v-1.5h2.75V6.5Z"/>
						</svg>
                    </div>
                    <div class="feature-title">Invite your employees</div>
					</div>
                    <div class="feature-desc">Add employees and manage your team from the dashboard.</div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 reveal reveal-delay-3 visible mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                <div class="feature-card">
                    <div class="feature-icon-box"><div class="feature-icon">
                       <svg width="35" height="35" viewBox="0 0 24 24" fill="currentColor" style="color:#1fda69" aria-hidden="true">
						  <path fill-rule="evenodd" d="M8.6 3.5h6.8a1.5 1.5 0 0 1 1.3.8l1.1 2.2H20a2 2 0 0 1 2 2V19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8.5a2 2 0 0 1 2-2h2.2l1.1-2.2a1.5 1.5 0 0 1 1.3-.8ZM12 9a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9Zm0 2.25a2.25 2.25 0 1 1 0 4.5 2.25 2.25 0 0 1 0-4.5Zm6.5-2.65a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8Z"/>
						</svg>
                    </div>
                    <div class="feature-title">Start capturing photos</div>
					</div>
                    <div class="feature-desc">Employees can capture and upload photos through the Photo Proof app.</div>
                </div>
            </div>
			
        </div>

    </div>
</div>


<div class="second-row bg-lightgrey wide-100 division" style="background-image: url(../user/images/tra-waves.png); background-repeat:no-repeat;    background-position: bottom;">
            <div class="container">
               <div class="row d-flex align-items-center">
                  <h2 class="h2-xs animated fadeInLeft visible text-center mb-5 w-100" data-animation="fadeInRight" data-animation-delay="300">
                     How Photo Proof Stops<br><span class="text-white">Common Delivery Fraud</span>
                  </h2>
                  <div class="col-md-12">
                     <div class="row g-4 align-items-stretch">
                        <div class="col-md-6 reveal reveal-delay-1 visible">
                           <div class="fraud-col fraud-col-without h-100">
                              <div class="d-flex align-items-center gap-3 mb-3 animated fadeInRight visible" data-animation="fadeInRight" data-animation-delay="400">
                                 <div style="width:42px;height:42px; margin-right:20px; border-radius:11px;background:rgba(220,38,38,.1);border:1px solid rgba(220,38,38,.25);display:flex;align-items:center;justify-content:center;">
                                    <svg class="svg-icon" width="35" height="35" style="color:#EF4444">
                                       <use href="#ic-x-circle"></use>
                                    </svg>
                                 </div>
                                 <span style="font-size:20px;font-weight:700;color:#EF4444;">Without Photo Proof</span>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#EF4444;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-x-circle"></use>
                                 </svg>
                                 <div><strong>Recycled Delivery Shots</strong> — Drivers reuse past drop-off images to claim fake, duplicate, or unattempted drop-offs.</div>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#EF4444;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-x-circle"></use>
                                 </svg>
                                 <div><strong>Spoofed Drop Locations</strong> — Delivery photos or device logs display falsified coordinates miles away from the actual consignee.</div>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#EF4444;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-x-circle"></use>
                                 </svg>
                                 <div><strong>Falsified Timestamps</strong> — Arrival times and drop dates are manually shifted on the handset to mask missed SLA windows.</div>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#EF4444;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-x-circle"></use>
                                 </svg>
                                 <div><strong>Offline Tampering</strong> — Images and metadata are edited directly on the phone storage before batch syncing later.</div>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#EF4444;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-x-circle"></use>
                                 </svg>
                                 <div><strong>Zero Chain of Custody</strong> — No verifiable link tying the image file back to the original waybill, driver, or route.</div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 reveal reveal-delay-2 visible">
                           <div class="fraud-col fraud-col-with h-100">
                              <div class="d-flex align-items-center gap-3 mb-3 animated fadeInRight visible" data-animation="fadeInRight" data-animation-delay="400">
                                 <div style="width:42px;height:42px; margin-right:20px; border-radius:11px;background:rgba(0,188,212,.1);border:1px solid rgba(0,188,212,.25);display:flex;align-items:center;justify-content:center;">
                                    <svg class="svg-icon" width="35" height="35" style="color:#1fda69">
                                       <use href="#ic-shield-check-fill"></use>
                                    </svg>
                                 </div>
                                 <span style="font-size:20px;font-weight:700;color:#1fda69;">With Photo Proof</span>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#1fda69;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-check-circle"></use>
                                 </svg>
                                 <div><strong>Live In-App Capture Only</strong> — Photo Proof requires real-time camera capture on-site; device gallery uploads and screenshots are blocked.</div>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#1fda69;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-check-circle"></use>
                                 </svg>
                                 <div><strong>Mandatory GPS Lock</strong> — Photo Proof enforces geofenced validation; no satellite coordinates means no capture allowed.</div>
                              </div>
                            
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#1fda69;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-check-circle"></use>
                                 </svg>
                                 <div><strong>Server-Validated Connection</strong> — Photo Proof verifies device integrity against dispatch servers before authenticating capture.</div>
                              </div>
							   <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#1fda69;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-check-circle"></use>
                                 </svg>
                                 <div><strong>Tamper-Proof Server Timestamps</strong> — All capture metadata is certified by central servers, preventing local clock manipulation.</div>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#1fda69;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-check-circle"></use>
                                 </svg>
                                 <div><strong>Unique Waybill Audit Trail</strong> — Photo Proof tags every image with an encrypted, traceable asset ID tied directly to the consignment note.</div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="fraud-highlight reveal reveal-delay-3 mt-4 visible">
                        <p style="font-size:16px; letter-spacing:2px;text-transform:uppercase;margin-bottom:10px;">The Photo Proof Rule</p>
                        <div class="fraud-highlight-text">"No Internet. No GPS. No Capture."</div>
                        <p style="font-size:18px;color:var(--text-secondary);margin-top:10px;margin-bottom:0;">Three automated safeguards guaranteeing every delivery confirmation is genuine, timestamped in real time, and location-verified at the consignee door.</p>
                     </div>
                  </div>
               </div>
			   		 <div class="row">
                  <div class="col-lg-10 offset-lg-1">
                     <div class="join-team text-center m-top-25">
                        <!-- Title -->
                        <h4 class="h4-md text-white"><span>Need something tailored to your team?</span></h4>
                        <!-- Text -->	
                        <p>Custom workflows, higher photo volumes, integrations with your existing systems, white-labeling, or on-premise requirements. Tell us what you need and we'll put together a solution.
                        </p>
						<a class="btn btn-lightgreen mt-2" href="https://photoproof.cogniter.com/#contact-us" target="_blank">TALK TO OUR TEAM<i class="bi bi-arrow-right ms-1"></i></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>


<!-- <section class="organization-page" id="create-account">
    <div class="organization-shell">

        <div class="organization-heading">
            <h2 class="h2-xs animated fadeInUp visible" data-animation="fadeInUp" data-animation-delay="300">Create your <span class="text-white">corporate account</span></h2>
            <p>It takes a couple of minutes. Your team can start capturing photos as soon as you're set up.</p>
        </div>

        <div class="organization-onboarding">

            {{-- Form card --}}
            <div class="organization-card">
                <div class="organization-card-header">
                    <span class="organization-card-icon"><i class="bi bi-buildings"></i></span>
                    <div>
                        <h2>Corporate account details</h2>
                        <p>Fill in the details below to register your company with Photo Proof.</p>
                    </div>
                </div>

                @if(session('success'))
                    <div id="flash-message" class="organization-alert organization-alert-success" role="status"><i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span></div>
                @endif

                @if($errors->any())
                    <div id="flash-message" class="organization-alert organization-alert-danger" role="alert"><i class="bi bi-exclamation-triangle-fill"></i><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif

                <form method="POST" action="{{ route('organization.store') }}">
                    @csrf

                    @if($selectedPlan)
                        <input type="hidden" name="subscription_plan" value="{{ $selectedPlan->id }}">
                        <input type="hidden" name="billing_cycle" value="{{ $billingCycle }}">
                        <div class="organization-plan-summary">
                            <i class="bi bi-patch-check-fill"></i>
                            <div>
                                <small>Selected plan</small>
                                <strong>{{ $selectedPlan->name }} &nbsp;|&nbsp; ${{ number_format($billingCycle === 'yearly' ? $selectedPlan->yearly_price : $selectedPlan->monthly_price, 2) }} / {{ $billingCycle === 'yearly' ? 'year' : 'month' }} &nbsp;|&nbsp; {{ number_format($selectedPlan->monthly_photo_limit) }} photos / month</strong>
                            </div>
                        </div>
                    @endif

                    <div class="organization-form-section">
                        <h3 class="organization-section-title"><i class="bi bi-building"></i> Organization information</h3>
                        <div class="organization-form-grid">
                            <div class="organization-form-group full">
                                <label for="organization_name">Organization name <span class="organization-required">*</span></label>
                                <input id="organization_name" type="text" name="organization_name" placeholder="Enter organization name" value="{{ old('organization_name') }}" required>
                            </div>
                            <div class="organization-form-group">
                                <label for="business_type">Business type</label>
                                <input id="business_type" type="text" name="business_type" placeholder="e.g. Delivery, logistics" value="{{ old('business_type') }}">
                            </div>
							<div class="organization-form-group">
                                <label for="owner_name">Contact person name</label>
                                <input id="owner_name" type="text" name="owner_name" placeholder="Enter contact person name" value="{{ old('owner_name') }}" autocomplete="name">
                            </div>
                            <div class="organization-form-group">
                                <label for="mobile_number">Contact person mobile number</label>
                                <input id="mobile_number" type="tel" name="mobile_number" placeholder="Enter mobile number" value="{{ old('mobile_number') }}" autocomplete="tel">
                            </div>
                            <div class="organization-form-group ">
                                <label for="organization_email">Contact person email address <span class="organization-required">*</span></label>
                                <input id="organization_email" type="email" name="organization_email" placeholder="Enter contact person email" value="{{ old('organization_email') }}" autocomplete="email" required>
                            </div>
							<div class="organization-form-group">
                                <label for="password">Password <span class="organization-required">*</span></label>
                                <input id="password" type="password" name="password" placeholder="Enter password" autocomplete="new-password" required>
                            </div>
                            <div class="organization-form-group">
                                <label for="password_confirmation">Confirm password <span class="organization-required">*</span></label>
                                <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm password" autocomplete="new-password" required>
                            </div>
							<div class="organization-form-group full">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" placeholder="Tell us anything that will help us support your team" rows="4">{{ old('message') }}</textarea>
                            </div>
                            <div class="organization-form-group full organization-captcha ">
                                <label>Security verification <span class="organization-required">*</span></label>
                                <div class="g-recaptcha" data-theme="dark" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                            </div>
                            <div class="organization-form-group full">
                                <label class="organization-terms" for="terms">
                                    <input type="checkbox" id="terms" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                                    <span>I agree to the
                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#organizationTermsModal">Corporate Account Terms &amp; Conditions</a>
                                        <span class="organization-required">*</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                  

                    <input type="hidden" name="initial_landing_page" id="initial_landing_page">
                    <input type="hidden" name="submitted_from" id="submitted_from">

                    <div class="organization-actions">
                        <button type="submit" class="organization-button-submit">Create corporate account <i class="bi bi-arrow-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Terms & Conditions Modal --}}
    <div class="modal fade" id="organizationTermsModal" tabindex="-1" aria-labelledby="organizationTermsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="organizationTermsModalLabel">Corporate Account Terms &amp; Conditions</h5>
                   
                </div>
                <div class="modal-body">
                    <h6>1. Organization Account</h6>
                    <p>By creating an organization account, you confirm that the information provided during registration is accurate and complete.</p>

                    <h6>2. Organization Administrator</h6>
                    <p>The organization owner or administrator is responsible for managing employees, organization information, and account access.</p>

                    <h6>3. Employee Accounts</h6>
                    <p>The organization administrator may invite employees to use the Photo Proof application. The administrator is responsible for ensuring that invited employees are authorized to use the service.</p>

                    <h6>4. Photo Uploads</h6>
                    <p>Uploaded photos must comply with applicable laws and must not contain prohibited, illegal, or unauthorized content.</p>

                    <h6>5. Subscription Plan</h6>
                    <p>Organization features and photo upload limits are determined by the selected subscription plan. Usage beyond the applicable plan limits may be restricted.</p>

                    <h6>6. Account Security</h6>
                    <p>You are responsible for maintaining the confidentiality of your login credentials and for all activities performed through your organization account.</p>

                    <h6>7. Suspension or Deactivation</h6>
                    <p>We reserve the right to suspend or deactivate an organization account if these terms are violated or if the account is used for unlawful activities.</p>

                    <h6>8. Acceptance</h6>
                    <p>By checking the "I agree" checkbox and creating an organization account, you confirm that you have read, understood, and agreed to these Terms &amp; Conditions.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-agree" data-bs-dismiss="modal" onclick="document.getElementById('terms').checked = true;">I agree</button>
                </div>
            </div>
        </div>
    </div>
</section> -->


<!--	<section id="newsletter-1" class="bg-scroll bg-green newsletter-section division">
            <div class="container white-color">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="row align-items-center g-4 reveal reveal-delay-1 visible">
                        <div class="col-lg-3 stepflow">
                           <div class="step-card">
                             
                              <div class="step-title">Capture & Manage</div>
                              <div class="step-detail">Capture photos in real time and keep everything organized in one place.</div>
                              <div class="step-bottom">
                                 <svg width="100" height="100" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								   <rect x="6" y="14" width="26" height="20" rx="4" stroke="#38d762" stroke-width="2"/>
								   <path d="M15 14L17 10H23L25 14" stroke="#38d762" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								   <circle cx="19" cy="24" r="5.5" stroke="#38d762" stroke-width="2"/>
								   <circle cx="27" cy="19" r="1.2" fill="#38d762"/>
								   <path d="M34 38A2 2 0 0 1 36 36H43L46 39H54A2 2 0 0 1 56 41V52A2 2 0 0 1 54 54H36A2 2 0 0 1 34 52V38Z" stroke="#38d762" stroke-width="2" stroke-linejoin="round"/>
								   <path d="M40 45H50" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
								   <path d="M40 49H46" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
								    <path d="M19 38V45H28" stroke="#38d762" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								   <path d="M25 42L28 45L25 48" stroke="#38d762" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
                              </div>
                           </div>
                          
                        </div>
                        <div class="col-lg-3 stepflow">
                           <div class="step-card">
                              
                              <div class="step-title">Location Verified</div>
                              <div class="step-detail">Keep important location information connected with every photo.</div>
                              <div class="step-bottom">
                                 <svg width="100" height="100" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								    <path d="M32 52C32 52 48 38.5 48 26A16 16 0 0 0 16 26C16 38.5 32 52 32 52Z" stroke="#38d762" stroke-width="2" stroke-linejoin="round"/>
								   <path d="M25 26L30 31L39 21" stroke="#38d762" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								   <ellipse cx="32" cy="56" rx="14" ry="3" stroke="#38d762" stroke-width="2"/>
								</svg>
                              </div>
                           </div>
                          
                        </div>
                        <div class="col-lg-3 stepflow">
                           <div class="step-card">
                             
                              <div class="step-title">Team Collaboration</div>
                              <div class="step-detail">Invite your team, assign roles and track photo uploads effortlessly.</div>
                              <div class="step-bottom">
                                 <svg width="100" height="100" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								   <circle cx="32" cy="24" r="6" stroke="#38d762" stroke-width="2"/>
								   <path d="M20 48A12 12 0 0 1 44 48" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
								   <circle cx="15" cy="29" r="4.5" stroke="#38d762" stroke-width="2"/>
								   <path d="M6 48A9 9 0 0 1 18.5 39.7" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
								   <circle cx="49" cy="29" r="4.5" stroke="#38d762" stroke-width="2"/>
								   <path d="M58 48A9 9 0 0 0 45.5 39.7" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
								   <path d="M32 6V13" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
								   <path d="M28.5 9.5L32 6L35.5 9.5" stroke="#38d762" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								   <path d="M6 54H58" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
								</svg>
                              </div>
                           </div>
                        </div>
						 <div class="col-lg-3 stepflow">
                           <div class="step-card">
                             
                              <div class="step-title">Secure & Reliable</div>
                              <div class="step-detail">Keep organizational data secure and accessible only to authorized users.</div>
                              <div class="step-bottom">
                                 <svg width="100" height="100" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								   <path d="M32 6L52 13V28C52 41 43.5 51 32 56C20.5 51 12 41 12 28V13L32 6Z" stroke="#38d762" stroke-width="2" stroke-linejoin="round"/>
								    <path d="M27 29V25A5 5 0 0 1 37 25V29" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
								   <rect x="24" y="29" width="16" height="12" rx="2" stroke="#38d762" stroke-width="2"/>
								   <circle cx="32" cy="34" r="1.5" fill="#38d762"/>
								   <path d="M32 35.5V38" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
								</svg>
                              </div>
                           </div>
                        </div>
						
                     </div>
                  </div>
               </div>
            </div>
         </section> -->
		 
		 
		  {{-- Terms & Conditions Modal --}}
    <div class="modal fade" id="organizationTermsModal" tabindex="-1" aria-labelledby="organizationTermsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="organizationTermsModalLabel">Corporate Account Terms &amp; Conditions</h5>
                    <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>-->
                </div>
                <div class="modal-body">
                    <h6>1. Organization Account</h6>
                    <p>By creating an organization account, you confirm that the information provided during registration is accurate and complete.</p>

                    <h6>2. Organization Administrator</h6>
                    <p>The organization owner or administrator is responsible for managing employees, organization information, and account access.</p>

                    <h6>3. Employee Accounts</h6>
                    <p>The organization administrator may invite employees to use the Photo Proof application. The administrator is responsible for ensuring that invited employees are authorized to use the service.</p>

                    <h6>4. Photo Uploads</h6>
                    <p>Uploaded photos must comply with applicable laws and must not contain prohibited, illegal, or unauthorized content.</p>

                    <h6>5. Subscription Plan</h6>
                    <p>Organization features and photo upload limits are determined by the selected subscription plan. Usage beyond the applicable plan limits may be restricted.</p>

                    <h6>6. Account Security</h6>
                    <p>You are responsible for maintaining the confidentiality of your login credentials and for all activities performed through your organization account.</p>

                    <h6>7. Suspension or Deactivation</h6>
                    <p>We reserve the right to suspend or deactivate an organization account if these terms are violated or if the account is used for unlawful activities.</p>

                    <h6>8. Acceptance</h6>
                    <p>By checking the "I agree" checkbox and creating an organization account, you confirm that you have read, understood, and agreed to these Terms &amp; Conditions.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-agree" data-bs-dismiss="modal" onclick="document.getElementById('terms').checked = true;">I agree</button>
                </div>
            </div>
        </div>
    </div>
		 
<script>
document.addEventListener('DOMContentLoaded', function () {
    const initialLandingPage = sessionStorage.getItem('initial_landing_page');
    const initialLandingField = document.getElementById('initial_landing_page');
    if (initialLandingField) {
        initialLandingField.value = initialLandingPage || window.location.href;
    }

    const submittedFromField = document.getElementById('submitted_from');
    if (submittedFromField) {
        submittedFromField.value = window.location.href;
    }
});
</script>
@endsection
