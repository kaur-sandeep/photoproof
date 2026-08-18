@extends('user.layouts.master')

@section('content')
<style>
    .organization-page { --org-blue:#1769e0; --org-navy:#14213d; --org-sky:#f3f8ff; --org-line:#dfe7f2; --org-muted:#64748b; padding:90px 16px 74px; background:linear-gradient(180deg, #f6faff 0, #fff 48%); overflow:hidden; }
    .organization-page * { box-sizing:border-box; }
    .organization-shell { max-width:1220px; margin:0 auto; }
    .organization-onboarding { display:grid; grid-template-columns:minmax(0, .92fr) minmax(480px, 1.08fr); gap:58px; align-items:center; }
    .organization-intro { position:relative; color:var(--org-navy); padding:32px 0; }
    .organization-kicker { display:inline-flex; align-items:center; gap:8px; padding:7px 11px; border-radius:999px; background:#e8f1ff; color:#205fc4; font-size:.74rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; }
    .organization-intro h1 { max-width:510px; margin:20px 0 16px; color:var(--org-navy); font-family:Montserrat, sans-serif; font-size:clamp(2.2rem, 4vw, 3.5rem); font-weight:700; line-height:1.14; letter-spacing:-.04em; }
    .organization-intro > p { max-width:525px; margin:0; color:#50627e; font-size:1.04rem; line-height:1.7; }
    .organization-features { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-top:34px; margin-bottom: 43px; }
    .organization-feature { display:flex; gap:12px; min-height:109px; padding:17px; border:1px solid #e3ebf6; border-radius:14px; background:rgba(255,255,255,.82); box-shadow:0 7px 24px rgba(26, 69, 123, .045); }
    .organization-feature-icon { display:grid; flex:0 0 38px; width:38px; height:38px; place-items:center; border-radius:11px; background:#eaf3ff; color:var(--org-blue); font-size:1.05rem; }
    .organization-feature h2 { margin:1px 0 5px; color:#263750; font-size:.92rem; font-weight:700; }
    .organization-feature p { margin:0; color:#718096; font-size:.79rem; line-height:1.5; }
    .organization-support-note { display:flex; gap:9px; align-items:flex-start; margin:25px 0 0; color:#5f718b; font-size:.85rem; line-height:1.55; }
    .organization-support-note i { color:#2fa86d; font-size:1rem; }
    .organization-card { position:relative; padding:34px; border:1px solid rgba(211, 223, 239, .9); border-radius:20px; background:#fff; box-shadow:0 18px 55px rgba(26, 65, 118, .13); }
    .organization-card::before { position:absolute; top:0; left:38px; right:38px; height:3px; border-radius:0 0 6px 6px; background:linear-gradient(90deg, #2775e8, #74adff); content:""; }
    .organization-card-header { margin-bottom:26px; }
    .organization-card-icon { display:grid; width:43px; height:43px; margin-bottom:14px; place-items:center; border-radius:12px; background:linear-gradient(135deg, #1e72e8, #4e9cff); color:#fff; font-size:1.25rem; box-shadow:0 7px 16px rgba(31, 112, 228, .26); }
    .organization-card h2 { margin:0 0 7px; color:var(--org-navy); font-family:Montserrat, sans-serif; font-size:1.48rem; font-weight:700; }
    .organization-card-header p { margin:0; color:var(--org-muted); font-size:.89rem; line-height:1.5; }
    .organization-alert { display:flex; gap:10px; align-items:flex-start; margin:0 0 22px; padding:13px 14px; border-radius:10px; font-size:.88rem; line-height:1.45; }
    .organization-alert i { margin-top:2px; font-size:1rem; }
    .organization-alert-success { border:1px solid #bce8d0; background:#effbf4; color:#18754a; }
    .organization-alert-danger { border:1px solid #f4c5c5; background:#fff4f4; color:#a53636; }
    .organization-alert ul { margin:0; padding-left:18px; }
    .organization-form-section + .organization-form-section { margin-top:23px; padding-top:23px; border-top:1px solid #edf1f6; }
    .organization-plan-summary { display:flex; align-items:center; gap:12px; margin:0 0 23px; padding:14px 15px; border:1px solid #cce6d6; border-radius:11px; background:#f1fbf5; color:#245b3b; }
    .organization-plan-summary i { display:grid; width:34px; height:34px; place-items:center; border-radius:9px; background:#d7f3e1; color:#18834a; font-size:1rem; }
    .organization-plan-summary small { display:block; color:#578067; font-size:.72rem; font-weight:700; letter-spacing:.05em; text-transform:uppercase; }
    .organization-plan-summary strong { color:#1d5435; font-size:.9rem; }
    .organization-section-title { display:flex; align-items:center; gap:8px; margin:0 0 15px; color:#34465f; font-size:.82rem; font-weight:700; letter-spacing:.025em; }
    .organization-section-title i { color:#3d7ce2; font-size:.92rem; }
    .organization-form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    .organization-form-group { min-width:0; }
    .organization-form-group.full { grid-column:1 / -1; }
    .organization-form-group label { display:block; margin:0 0 7px; color:#42536a; font-size:.79rem; font-weight:700; }
    .organization-required { color:#d84b4b; }
    .organization-form-group input:not([type="checkbox"]), .organization-form-group textarea { width:100%; min-height:47px; padding:12px 13px; border:1px solid #d9e2ef; border-radius:9px; background:#fff; color:#23324a; font:400 .88rem Roboto, sans-serif; outline:0; transition:border-color .18s, box-shadow .18s; }
    .organization-form-group textarea { min-height:94px; resize:vertical; }
    .organization-form-group input::placeholder, .organization-form-group textarea::placeholder { color:#9aa8ba; }
    .organization-form-group input:not([type="checkbox"]):focus, .organization-form-group textarea:focus { border-color:#4a8bed; box-shadow:0 0 0 3px rgba(53, 124, 230, .13); }
    .organization-captcha { overflow-x:auto; padding:2px; }
    .organization-terms { display:flex; align-items:flex-start; gap:9px; color:#596a80; font-size:.82rem; line-height:1.5; cursor:pointer; }
    .organization-terms input { width:16px; height:16px; margin:2px 0 0; accent-color:var(--org-blue); }
    .organization-terms a { color:#216cd4; font-weight:700; text-decoration:none; }
    .organization-terms a:hover { text-decoration:underline; }
    .organization-actions { display:flex; justify-content:flex-end; gap:12px; margin-top:27px; padding-top:22px; border-top:1px solid #edf1f6; }
    .organization-button { display:inline-flex; min-height:45px; align-items:center; justify-content:center; gap:8px; padding:10px 18px; border-radius:9px; font-size:.86rem; font-weight:700; transition:.2s ease; }
    .organization-button-cancel { border:1px solid #d7e0eb; background:#fff; color:#53647c; }
    .organization-button-cancel:hover { border-color:#b8c6d8; background:#f8fafc; color:#263750; }
    .organization-button-submit { border:1px solid #1769e0; background:linear-gradient(135deg, #1769e0, #317fe9); color:#fff; box-shadow:0 7px 14px rgba(23, 105, 224, .2); }
    .organization-button-submit:hover { transform:translateY(-1px); background:#105ecf; color:#fff; box-shadow:0 10px 18px rgba(23, 105, 224, .28); }
    .organization-how-it-works { margin-top:76px; padding-top:55px; border-top:1px solid #e5edf7; }
    .organization-how-header { max-width:570px; margin:0 auto 30px; text-align:center; }
    .organization-how-header span { color:#2d74d7; font-size:.73rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; }
    .organization-how-header h2 { margin:8px 0; color:var(--org-navy); font-family:Montserrat, sans-serif; font-size:1.75rem; font-weight:700; }
    .organization-how-header p { margin:0; color:#6b7b91; font-size:.92rem; }
    .organization-steps { display:grid; grid-template-columns:repeat(3, 1fr); gap:20px; }
    .organization-step { position:relative; padding:24px; border:1px solid #e1eaf5; border-radius:14px; background:#fff; box-shadow:0 7px 22px rgba(31, 70, 124, .045); }
    .organization-step-number { display:block; margin-bottom:16px; color:#3e82e6; font-size:.76rem; font-weight:800; letter-spacing:.08em; }
    .organization-step h3 { margin:0 0 8px; color:#263750; font-size:1rem; font-weight:700; }
    .organization-step p { margin:0; color:#6b7b91; font-size:.85rem; line-height:1.6; }
    @media (max-width:991px) { .organization-page { padding-top:125px; } .organization-onboarding { grid-template-columns:1fr; gap:28px; } .organization-intro { max-width:700px; padding:0; } .organization-card { max-width:720px; } }
    @media (max-width:575px) { .organization-page { padding:80px 12px 52px; } .organization-intro h1 { font-size:2.15rem; } .organization-features, .organization-form-grid, .organization-steps { grid-template-columns:1fr; } .organization-feature { min-height:auto; } .organization-card { padding:26px 19px; border-radius:15px; } .organization-card::before { left:25px; right:25px; } .organization-actions { flex-direction:column-reverse; } .organization-button { width:100%; } .organization-how-it-works { margin-top:52px; padding-top:43px; } .organization-captcha { max-width:100%; } }
</style>
<section class="breadcrumb">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						 <nav aria-label="breadcrumb">
							<ol class="breadcrumb-list">
								<li><a href="/">Home</a></li>
								
								<li>Create Corporate Account </li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
</section>

<section class="organization-page">
    <div class="organization-shell">
        <div class="organization-onboarding">
            <div class="organization-intro">
                <span class="organization-kicker"><i class="bi bi-shield-check"></i> PhotoProof for teams</span>
                <h1>Smart Photo Management<br>for Modern Teams</h1>
                <p>PhotoProof helps organizations capture, manage and verify photos from field teams with location, time and complete accuracy.</p>

                <div class="organization-features">
                    <article class="organization-feature"><span class="organization-feature-icon"><i class="bi bi-camera"></i></span><div><h2>Capture &amp; Manage</h2><p>Capture photos in real time and keep everything organized in one place.</p></div></article>
                    <article class="organization-feature"><span class="organization-feature-icon"><i class="bi bi-geo-alt"></i></span><div><h2>Location Verified</h2><p>Keep important location information connected with every photo.</p></div></article>
                    <article class="organization-feature"><span class="organization-feature-icon"><i class="bi bi-people"></i></span><div><h2>Team Collaboration</h2><p>Invite your team, assign roles and track photo uploads effortlessly.</p></div></article>
                    <article class="organization-feature"><span class="organization-feature-icon"><i class="bi bi-lock"></i></span><div><h2>Secure &amp; Reliable</h2><p>Keep organizational data secure and accessible only to authorized users.</p></div></article>
                </div>
                <p class="organization-support-note"><i class="bi bi-check-circle-fill"></i><span>Built for courier, delivery, field-service and other teams where photo proof matters.</span></p>
            </div>

            <div class="organization-card">
                <div class="organization-card-header">
                    <span class="organization-card-icon"><i class="bi bi-buildings"></i></span>
                    <h2>Create Corporate Account</h2>
                    <p>Fill in the details below to register your company with PhotoProof.</p>
                </div>

                @if(session('success'))
                    <div id="flash-message" class="organization-alert organization-alert-success"><i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span></div>
                @endif

                @if($errors->any())
                    <div id="flash-message" class="organization-alert organization-alert-danger"><i class="bi bi-exclamation-triangle-fill"></i><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif

                <form method="POST" action="{{ route('organization.store') }}">
                    @csrf
                    @if($selectedPlan)
                        <input type="hidden" name="subscription_plan" value="{{ $selectedPlan->id }}">
                        <div class="organization-plan-summary">
                            <i class="bi bi-patch-check-fill"></i>
                            <div><small>Selected plan <span class="organization-required">*</span></small><strong>{{ $selectedPlan->name }} — ₹{{ number_format($billingCycle === 'yearly' ? $selectedPlan->yearly_price : $selectedPlan->monthly_price, 2) }} / {{ $billingCycle === 'yearly' ? 'year' : 'month' }} — {{ number_format($selectedPlan->monthly_photo_limit) }} photos / month</strong></div>
                        </div>
                        <input type="hidden" name="billing_cycle" value="{{ $billingCycle }}">
                    @endif
                    <div class="organization-form-section">
                        <h3 class="organization-section-title"><i class="bi bi-building"></i> Organization Information</h3>
                        <div class="organization-form-grid">
                            <div class="organization-form-group"><label for="organization_name">Organization Name <span class="organization-required">*</span></label><input id="organization_name" type="text" name="organization_name" placeholder="Enter organization name" value="{{ old('organization_name') }}"></div>
                            <div class="organization-form-group"><label for="business_type">Business Type</label><input id="business_type" type="text" name="business_type" placeholder="e.g. Delivery, logistics" value="{{ old('business_type') }}"></div>
                        </div>
                    </div>

                    <div class="organization-form-section">
                        <h3 class="organization-section-title"><i class="bi bi-person"></i> Contact Information</h3>
                        <div class="organization-form-grid">
                            <div class="organization-form-group"><label for="owner_name">Contact Person Name</label><input id="owner_name" type="text" name="owner_name" placeholder="Enter contact person name" value="{{ old('owner_name') }}"></div>
                            <div class="organization-form-group"><label for="mobile_number">Contact Person Mobile Number</label><input id="mobile_number" type="text" name="mobile_number" placeholder="Enter mobile number" value="{{ old('mobile_number') }}"></div>
                            <div class="organization-form-group full"><label for="organization_email">Contact Person Email Address <span class="organization-required">*</span></label><input id="organization_email" type="email" name="organization_email" placeholder="Enter contact person email" value="{{ old('organization_email') }}"></div>
                        </div>
                    </div>

                    <div class="organization-form-section">
                        <h3 class="organization-section-title"><i class="bi bi-shield-lock"></i> Account Security</h3>
                        <div class="organization-form-grid">
                            <div class="organization-form-group"><label for="password">Password <span class="organization-required">*</span></label><input id="password" type="password" name="password" placeholder="Enter password" value="{{ old('password') }}"></div>
                            <div class="organization-form-group"><label for="password_confirmation">Confirm Password <span class="organization-required">*</span></label><input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm password" value="{{ old('password_confirmation') }}"></div>
                        </div>
                    </div>

                    <div class="organization-form-section">
                        <h3 class="organization-section-title"><i class="bi bi-chat-left-text"></i> Additional Information</h3>
                        <div class="organization-form-grid">
                            <div class="organization-form-group full"><label for="message">Message</label><textarea id="message" name="message" placeholder="Tell us anything that will help us support your team" rows="4">{{ old('message') }}</textarea></div>
                            <div class="organization-form-group full organization-captcha"><label>Security verification <span class="organization-required">*</span></label><div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div></div>
                            <div class="organization-form-group full form-check">
                                <!-- <label class="organization-terms">
                                    <input type="checkbox" name="terms" style="margin-right: 10px;" value="1" {{ old('terms') ? 'checked' : '' }}>
                                    <span> I agree to the <a href="{{ url('/terms-conditions') }}" target="_blank">Terms &amp; Conditions</a></span>
                                </label> -->
                          
                                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>

                                    <label class="form-check-label" for="terms">
                                        I agree to the <span class="organization-required">*</span>
                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#organizationTermsModal">
                                            Create Corporate Account Terms &amp; Conditions
                                        </a>
                                    </label>

                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="initial_landing_page" id="initial_landing_page">
                    <input type="hidden" name="submitted_from" id="submitted_from">
                    <div class="organization-actions">
                        <!-- <button type="button" class="organization-button organization-button-cancel" onclick="window.history.back()">Cancel</button> -->
                        <button type="submit" class="organization-button organization-button-submit">Create Corporate Account <i class="bi bi-arrow-right"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <section class="organization-how-it-works" aria-labelledby="how-it-works-title">
            <div class="organization-how-header"><span>Simple onboarding</span><h2 id="how-it-works-title">How PhotoProof works</h2><p>Set up your company and start building a clearer record of work in the field.</p></div>
            <div class="organization-steps">
                <article class="organization-step"><span class="organization-step-number">01</span><h3>Create your company</h3><p>Register your company and contact details.</p></article>
                <article class="organization-step"><span class="organization-step-number">02</span><h3>Invite your employees</h3><p>Add employees and manage your team from the dashboard.</p></article>
                <article class="organization-step"><span class="organization-step-number">03</span><h3>Start capturing photos</h3><p>Employees can capture and upload photos through the PhotoProof app.</p></article>
            </div>
        </section>
    </div>
        <!-- Create Organization Terms & Conditions Modal -->
    <div class="modal fade" id="organizationTermsModal" tabindex="-1"
        aria-labelledby="organizationTermsModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="organizationTermsModalLabel">
                        Create Corporate Account Terms &amp; Conditions
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                    </button>
                </div>

                <div class="modal-body" style="color:black">

                    <h6>1. Organization Account</h6>
                    <p>
                        By creating an organization account, you confirm that the
                        information provided during registration is accurate and complete.
                    </p>

                    <h6>2. Organization Administrator</h6>
                    <p>
                        The organization owner or administrator is responsible for
                        managing employees, organization information, and account access.
                    </p>

                    <h6>3. Employee Accounts</h6>
                    <p>
                        The organization administrator may invite employees to use the
                        PhotoProof application. The administrator is responsible for
                        ensuring that invited employees are authorized to use the service.
                    </p>

                    <h6>4. Photo Uploads</h6>
                    <p>
                        Uploaded photos must comply with applicable laws and must not
                        contain prohibited, illegal, or unauthorized content.
                    </p>

                    <h6>5. Subscription Plan</h6>
                    <p>
                        Organization features and photo upload limits are determined by
                        the selected subscription plan. Usage beyond the applicable plan
                        limits may be restricted.
                    </p>

                    <h6>6. Account Security</h6>
                    <p>
                        You are responsible for maintaining the confidentiality of your
                        login credentials and for all activities performed through your
                        organization account.
                    </p>

                    <h6>7. Suspension or Deactivation</h6>
                    <p>
                        We reserve the right to suspend or deactivate an organization
                        account if these terms are violated or if the account is used
                        for unlawful activities.
                    </p>

                    <h6>8. Acceptance</h6>
                    <p>
                        By checking the "I agree" checkbox and creating an organization
                        account, you confirm that you have read, understood, and agreed
                        to these Terms &amp; Conditions.
                    </p>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="button"
                            class="btn btn-primary"
                            data-bs-dismiss="modal"
                            onclick="document.getElementById('terms').checked = true;">
                        I Agree
                    </button>
                </div>

            </div>
        </div>
    </div>
</section>

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
