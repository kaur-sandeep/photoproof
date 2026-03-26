@extends('user.layouts.master')

@section('content')

 
	<section class="bg-scroll bg-purple hero-section division hero-banner">
            <div class="container">
               <div class="row hero-row-200">
                  <div class="col-md-6 d-flex align-items-center">
                     <div class="hero-txt white-color">
                        <h3 class="h2-lg animated fadeInRight visible" data-animation="fadeInRight" data-animation-delay="300" style="color: #00d757; font-size: 38px;">
						
                           We're Sorry To See You Go
                        </h3>
                        <p class="p-lg animated fadeInUp visible" data-animation="fadeInUp" data-animation-delay="400">
                           You have successfully unsubscribed from the Photo Proof mailing list.
                        </p>
						
						<h4><span style="text-align: center; font-weight:500; padding-top: 15px;font-size: 18px;">Your time zone is </span> 
                    <span style="font-size: 18px;     color: #00d757;" id="timezone" data-timezone="" class="notranslate"></span></h4>
                       
                     </div>
                  </div>
                  <div class="col-md-6 text-right">
                     <img src="https://photoproof.cogniter.com/user/images/image-09.png" class="img-fluid heroimg" id="hdrright">
                  </div>
               </div>
            </div>
         </section>

  
<section id="team-1" class="bg-fixed bg-lightgrey wide-50 team-section division" style="background-image: url(https://photoproof.cogniter.com/user/images/tra-waves.png); background-repeat:no-repeat;">
            <div class="container">
               <!-- SECTION TITLE -->	
               <div class="row">
                  <div class="col-md-10 offset-md-1 section-title">
                     <!-- Title 	-->	
                     <h2 class="h2-lg">Industries That<br>Benefit from Photo Proof</h2>
                     <!-- Text -->
                     <p>Photo Proof is ideal for sectors where visual evidence must be trusted:
                     </p>
                  </div>
                  <!-- End row --> 	
               </div>
               <!-- END SECTION TITLE -->	
               <div class="row g-4 justify-content-center">
                  <div class="col-6 col-md-4 col-lg-4 reveal reveal-delay-1 visible  mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="industry-card">
                        <div class="industry-icon-svg"><svg class="svg-icon" width="35" height="35" style="color:#38d762"><use href="#ic-construction"></use></svg></div>
                        <div class="industry-name">Construction &amp; Infrastructure</div>
                        <div class="industry-desc">Site progress, compliance, and safety documentation.</div>
                     </div>
                  </div>
                  <div class="col-6 col-md-4 col-lg-4 reveal reveal-delay-2 visible  mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="industry-card">
                        <div class="industry-icon-svg"><svg class="svg-icon" width="35" height="35" style="color:#38d762"><use href="#ic-home"></use></svg></div>
                        <div class="industry-name">Real Estate &amp; Property</div>
                        <div class="industry-desc">Verified property condition reports and inspections.</div>
                     </div>
                  </div>
                  <div class="col-6 col-md-4 col-lg-4 reveal reveal-delay-3 visible  mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="industry-card">
                        <div class="industry-icon-svg"><svg class="svg-icon" width="35" height="35" style="color:#38d762"><use href="#ic-truck"></use></svg></div>
                        <div class="industry-name">Logistics &amp; Supply Chain</div>
                        <div class="industry-desc">Proof of delivery, pickup, and cargo condition.</div>
                     </div>
                  </div>
                  <div class="col-6 col-md-4 col-lg-4 reveal reveal-delay-1 visible  mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="industry-card">
                        <div class="industry-icon-svg"><svg class="svg-icon" width="35" height="35" style="color:#38d762"><use href="#ic-banknote"></use></svg></div>
                        <div class="industry-name">Banking &amp; Financial</div>
                        <div class="industry-desc">KYC documentation and field verification.</div>
                     </div>
                  </div>
                  <div class="col-6 col-md-4 col-lg-4 reveal reveal-delay-2 visible  mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="industry-card">
                        <div class="industry-icon-svg"><svg class="svg-icon" width="35" height="35" style="color:#38d762"><use href="#ic-shield"></use></svg></div>
                        <div class="industry-name">Insurance</div>
                        <div class="industry-desc">Verified claims, damage assessment, and loss documentation.</div>
                     </div>
                  </div>
                  <div class="col-6 col-md-4 col-lg-4 reveal reveal-delay-3 visible  mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="industry-card">
                        <div class="industry-icon-svg"><svg class="svg-icon" width="35" height="35" style="color:#38d762"><use href="#ic-factory"></use></svg></div>
                        <div class="industry-name">Manufacturing &amp; QC</div>
                        <div class="industry-desc">Product quality control and production audits.</div>
                     </div>
                  </div>
                  <div class="col-6 col-md-4 col-lg-4 reveal reveal-delay-1 visible  mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="industry-card">
                        <div class="industry-icon-svg"><svg class="svg-icon" width="35" height="35" style="color:#38d762"><use href="#ic-heart-pulse"></use></svg></div>
                        <div class="industry-name">Healthcare &amp; Field Services</div>
                        <div class="industry-desc">Field visit verification and patient care documentation.</div>
                     </div>
                  </div>
                  <div class="col-6 col-md-4 col-lg-4 reveal reveal-delay-2 visible  mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="industry-card">
                        <div class="industry-icon-svg"><svg class="svg-icon" width="35" height="35" style="color:#38d762"><use href="#ic-landmark"></use></svg></div>
                        <div class="industry-name">Government &amp; Public Sector</div>
                        <div class="industry-desc">Official site inspections, audit compliance, and public records.</div>
                     </div>
                  </div>
               </div>
               <!-- End row -->
               <!-- JOIN TEAM BUTTON -->
               <div class="row">
                  <div class="col-lg-10 offset-lg-1">
                     <div class="join-team text-center m-top-25">
                        <!-- Title -->
                        <h4 class="h4-md text-white"><span>Why Organizations Choose Photo Proof?</span></h4>
                        <!-- Text -->	
                        <p>In a world where evidence can be fabricated, organizations need a solution they can trust unconditionally. Photo Proof delivers enterprise-grade documentation that stands up to legal scrutiny, audit requirements, and regulatory compliance.
                        </p>
                     </div>
                  </div>
               </div>
               <!-- END JOIN TEAM BUTTON -->
            </div>
         </section>

 @endsection

 <script>
    document.addEventListener("DOMContentLoaded", function () {
      const tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
      document.getElementById("timezone").innerText = tz;
    });
</script>