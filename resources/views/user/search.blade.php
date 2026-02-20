<!-- <form method="POST" action="{{ route('photo.search') }}">
    @csrf
    <input type="text" name="random_id"  value="{{ old('random_id') }}"  placeholder="Enter Photo Code" required>
    <button type="submit">Search</button>
</form>

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif -->

@extends('user.layouts.master')

@section('content')


 <section  class="bg-scroll bg-purple hero-section division hero-banner">
            <div class="container">
               <div class="row hero-row-200">
                  <div class="col-md-6 d-flex align-items-center">
                     <div class="hero-txt white-color">
                        <h2 class="h2-lg animated" data-animation="fadeInRight" data-animation-delay="300">
                           Don't Just Take Photos.<br><span class="text-white">Capture Proof.</span>
                        </h2>
                        <p class="p-lg animated" data-animation="fadeInUp" data-animation-delay="400">
                           With PhotoProof, every image becomes a verified document backed by live metadata and secure validation. Download PhotoProof today and ensure your visual records are trusted, traceable, and tamper-resistant.
                        </p>
                        <div class="hero-stores-badge animated" data-animation="fadeInUp" data-animation-delay="500">
                           <a href="#" class="store">
                           <img class="appstore-white" src="{{ asset('user/images/store_badges/appstore.png') }}" width="155" height="50" alt="appstore-logo">
                           </a>	
                           <a href="#" class="store">
                           <img class="googleplay-white" src="{{ asset('user/images/store_badges/googleplay.png') }}" width="164" height="50" alt="googleplay-logo">
                           </a>
                           <div class="os-version">
                              <span>
                                 <svg class="svg-icon" width="20" height="20" style="color:#38d762; margin-right:5px;">
                                    <use href="#ic-shield-check"></use>
                                 </svg>
                                 End-to-end encrypted
                              </span>
                              |							   
                              <span>
                                 <svg class="svg-icon" width="20" height="20" style="color:#38d762; margin-right:5px;">
                                    <use href="#ic-wifi-off"></use>
                                 </svg>
                                 No offline capture 
                              </span>
                              |	
                              <span>
                              <svg class="svg-icon" width="20" height="20" style="color:#38d762; margin-right:5px;">
                                 <use href="#ic-lock-fill"></use>
                              </svg>
                              Tamper-proof metadata
                              <span>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 text-right">
                     <img src="{{ asset('user/images/image-09.png')}}" class="img-fluid heroimg"/>
                  </div>
               </div>
            </div>
         </section>
         <section class="usecases bg-lightgrey" style="background: #0d1721; border-top: 2px solid #070707;">
            <div class="container">
               <div class="col-md-12 mx-auto text-center">
			      <!-- <div class="verifyphoto"> -->
                   <form method="POST" action="{{ route('photo.search') }}"  class="verifyphoto">
					<label>Verify a photo</label>
					<!-- <input type="text" name="photoid" class="form-control" placeholder="Enter Photo ID •  e.g. 9865XXXXX"> 
                    <a href="verify-photo.html" class="btn btn-lightgreen submit ">Verify</a> -->

                    
                        @csrf
                        <input type="text" name="random_id" class="form-control" value="{{ old('random_id') }}"  placeholder="Enter Photo ID •  e.g. 9865XXXXX" required >
                        <button  class="btn btn-lightgreen submit " type="submit">Verify</button>
                  
				  <!-- </div> -->

                </form>
                    @if(session('error'))
                        <p style="color:red">{{ session('error') }}</p>
                    @endif
               </div>
            </div>
         </section>
         <section id="content-3" class="bg-fixed content-section">
            <div class="first-row wide-60 division">
               <div class="container">
                  <div class="row d-flex align-items-center">
                     <div class="col-md-7 animated" data-animation="fadeInRight" data-animation-delay="500">
                        <div class="content-img">
                           <img class="img-fluid" src="{{ asset('user/images/featureimg.png')}}" alt="content-image" />
                        </div>
                     </div>
                     <div class="col-md-5">
                        <div class="content-txt ind-45">
                           <h2 class="h2-xs animated" data-animation="fadeInLeft" data-animation-delay="600">
                              Built for Real Proof, <span class="text-white">Not Just Pictures</span>
                           </h2>
                           <p class="animated" data-animation="fadeInLeft" data-animation-delay="600">
                              PhotoProof is a secure, enterprise-grade photo verification app engineered to capture indisputable evidence. Every photo is embedded with live GPS coordinates, precise date and time, local timezone, and a unique traceable Verification ID — all sourced from secured servers, not the device.
                           </p>
                           <p class="animated" data-animation="fadeInLeft" data-animation-delay="600">
                              Photos cannot be edited, reused, or uploaded from a gallery. The app requires an active internet connection and GPS signal to function, ensuring all metadata reflects real-world conditions at the exact moment of capture.
                           </p>
                           <div class="intro-highlight animated" data-animation="fadeInLeft" data-animation-delay="600">"When proof truly matters, PhotoProof delivers with confidence."</div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <section id="screens-2" class="wide-100 bg-lightgrey screens-section division">
            <!-- SECTION TITLE -->
            <div class="container">
               <div class="row">
                  <div class="col-md-10 offset-md-1 section-title">
                     <h2 class="h2-lg">Designed for<br/><span class="text-white">Simplicity & Precision</span></h2>
                     <p>A clean, intuitive interface that keeps the focus on capturing tamper-proof evidence with confidence.</p>
                  </div>
               </div>
            </div>
            <div class="screenshots-wrap">
               <div class="screens-carousel">
                  <div class="carousel-item">
                     <img src="{{ asset('user/images/portfolio/screen-1.png')}}" alt="screenshot">
                  </div>
                  <div class="carousel-item">
                     <img src="{{ asset('user/images/portfolio/screen-2.png')}}" alt="screenshot">
                  </div>
                  <div class="carousel-item">
                     <img src="{{ asset('user/images/portfolio/screen-3.png')}}" alt="screenshot">
                  </div>
                  <div class="carousel-item">
                     <img src="{{ asset('user/images/portfolio/screen-4.png')}}" alt="screenshot">
                  </div>
                  <div class="carousel-item">
                     <img src="{{ asset('user/images/portfolio/screen-5.png')}}" alt="screenshot">
                  </div>
                  <div class="carousel-item">
                     <img src="{{ asset('user/images/portfolio/screen-6.png')}}" alt="screenshot">
                  </div>
                  <div class="carousel-item">
                     <img src="{{ asset('user/images/portfolio/screen-7.png')}}" alt="screenshot">
                  </div>
                  <div class="carousel-item">
                     <img src="{{ asset('user/images/portfolio/screen-8.png')}}" alt="screenshot">
                  </div>
                  <div class="carousel-item">
                     <img src="{{ asset('user/images/portfolio/screen-9.png')}}" alt="screenshot">
                  </div>
               </div>
            </div>
         </section>
		 <div class="third-row wide-60 division">
            <div class="container-fluid px-2 px-lg-5">
               <div class="row">
                  <h2 class="h2-xs animated w-100 text-center mb-5" data-animation="fadeInLeft" data-animation-delay="300">
                     Common <span class="text-white">Usecases</span>
                  </h2>
                  <div class="col-sm-6 col-lg-4 reveal reveal-delay-1 visible mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="feature-card p-0 common-usecases">
                        <div class="usecases-num">01</div>
                        <img src="{{ asset('user/images/usecase1.png')}}" class="img-fluid"/>
						<h4>Buyer and Seller</h4>
                        <p>PhotoProof of item being with you with your current location and time embedded when your buyer asks for an image.</p>
                     </div>
                  </div>
                  <div class="col-sm-6 col-lg-4 reveal reveal-delay-1 visible mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="feature-card p-0 common-usecases">
                        <div class="usecases-num">02</div>
                        <img src="{{ asset('user/images/usecase2.png')}}" class="img-fluid"/>
						<h4>Verified Visit</h4>
                        <p>Undisputable proof that you were at a particular spot at a particular time when you took that selfie.</p>
                     </div>
                  </div>
                  <div class="col-sm-6 col-lg-4 reveal reveal-delay-1 visible mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="feature-card p-0 common-usecases">
                        <div class="usecases-num">03</div>
                        <img src="{{ asset('user/images/usecase3.png')}}" class="img-fluid"/>
						<h4>Proof of Delivery</h4>
                        <p>Proof that a package was dropped by you at your friend's address with the time stamp and location embedded in the photo.</p>
                     </div>
                  </div>
                  <div class="col-sm-6 col-lg-4 reveal reveal-delay-1 visible mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="feature-card p-0 common-usecases">
                        <div class="usecases-num">04</div>
                        <img src="{{ asset('user/images/usecase4.png')}}" class="img-fluid"/>
						<h4>Contractor and Client</h4>
                        <p>Capture construction site progress with live GPS, date, and time embedded for accurate proof. Enable trusted contractor validation and compliance with tamper-proof verified photos.</p>
                     </div>
                  </div>
                  <div class="col-sm-6 col-lg-4 reveal reveal-delay-1 visible mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="feature-card p-0 common-usecases">
                        <div class="usecases-num">05</div>
                        <img src="{{ asset('user/images/usecase5.png')}}" class="img-fluid"/>
						<h4>Owner and Tenant</h4>
                        <p>Capture property inspections with live GPS, date, and time embedded for reliable visual records. Document tenant move-in and move-out with tamper-proof verified photos for clear proof.</p>
                     </div>
                  </div>
                  <div class="col-sm-6 col-lg-4 reveal reveal-delay-1 visible mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="feature-card p-0 common-usecases">
                        <div class="usecases-num">06</div>
                        <img src="{{ asset('user/images/usecase6.png')}}" class="img-fluid"/>
						<h4>Lender and Field Officer</h4>
                        <p>Capture on-site field verification with live GPS, date, and time embedded for reliable records. Secure loan collateral inspection and KYC documentation with tamper-proof verified photos.</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <section id="newsletter-1" class="bg-scroll bg-green newsletter-section division">
            <div class="container white-color">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="newsletter-txt text-center mb-4">
                        <!-- Title -->
                        <h3 class="h3-xl">How It Works</h3>
                     </div>
                     <div class="row align-items-center g-4 reveal reveal-delay-1 visible">
                        <!-- Step 1 -->
                        <div class="col-lg-4 stepflow">
                           <div class="step-card">
                              <div class="step-num">01</div>
                              <!--<div class="step-icon-wrap">
                                 <svg class="svg-icon" width="35" height="35" style="color:#1fda69">
                                    <use href="#ic-phone"></use>
                                 </svg>
                                 </div> -->
                              <div class="step-title">Install &amp; Allow Access</div>
                              <div class="step-detail">Install the app and grant location and camera permissions. These are required to ensure metadata authenticity.</div>
                              <div class="step-bottom">
                                 <svg width="200" height="200" viewBox="0 0 72 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Phone -->
                                    <rect x="14" y="6" width="28" height="52" rx="6" stroke="#38d762" stroke-width="2"/>
                                    <!-- Download Arrow (Install) -->
                                    <path d="M28 16V30" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M24 26L28 30L32 26" stroke="#38d762" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <!-- Camera -->
                                    <rect x="22" y="36" width="14" height="10" rx="2" stroke="#38d762" stroke-width="2"/>
                                    <circle cx="29" cy="41" r="3" stroke="#38d762" stroke-width="2"/>
                                    <path d="M25 36L26.5 34H31.5L33 36" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
                                    <!-- Location Pin (More Outside / Right Side) -->
                                    <path d="M60 16C60 12.686 57.314 10 54 10C50.686 10 48 12.686 48 16C48 21 54 26 54 26C54 26 60 21 60 16Z" stroke="#38d762" stroke-width="2"/>
                                    <circle cx="54" cy="16" r="2" fill="#38d762"/>
                                 </svg>
                              </div>
                           </div>
                           <svg width="40" height="24" viewBox="0 0 40 24" fill="none" class="steparrow">
                              <path d="M0 12H38M38 12L28 2M38 12L28 22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                           </svg>
                        </div>
                        <!-- Step 2 -->
                        <div class="col-lg-4 stepflow">
                           <div class="step-card">
                              <div class="step-num">02</div>
                              <!--<div class="step-icon-wrap">
                                 <svg class="svg-icon" width="35" height="35" style="color:#1fda69">
                                    <use href="#ic-camera"></use>
                                 </svg>
                                 </div> -->
                              <div class="step-title">Take a Snap</div>
                              <div class="step-detail">App retrieves time, timezone, and exact location from secure servers and embeds it into the image. Data is NOT user-editable</div>
                              <div class="step-bottom">
                                 <svg width="200" height="200" viewBox="0 0 96 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <!-- Camera Body -->
                                    <rect x="28" y="20" width="40" height="28" rx="6" stroke="#38d762" stroke-width="2"/>
                                    <!-- Camera Top -->
                                    <path d="M40 20L43 16H53L56 20" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
                                    <!-- Camera Lens -->
                                    <circle cx="48" cy="34" r="7" stroke="#38d762" stroke-width="2"/>
                                    <circle cx="48" cy="34" r="3" fill="#38d762"/>
                                    <!-- Clock (Far Outside - Top Left) -->
                                    <circle cx="12" cy="18" r="6" stroke="#38d762" stroke-width="2"/>
                                    <path d="M12 18V14" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M12 18H16" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
                                    <!-- Location Pin (Far Outside - Top Right) -->
                                    <path d="M88 18C88 14.686 85.314 12 82 12C78.686 12 76 14.686 76 18C76 23 82 28 82 28C82 28 88 23 88 18Z"
                                       stroke="#38d762" stroke-width="2"/>
                                    <circle cx="82" cy="18" r="2" fill="#38d762"/>
                                    <!-- Server (Bottom Center - Outside) -->
                                    <rect x="40" y="54" width="16" height="6" rx="2" stroke="#38d762" stroke-width="2"/>
                                    <rect x="40" y="60" width="16" height="6" rx="2" stroke="#38d762" stroke-width="2"/>
                                    <circle cx="44" cy="57" r="1" fill="#38d762"/>
                                    <circle cx="44" cy="63" r="1" fill="#38d762"/>
                                 </svg>
                              </div>
                           </div>
                           <svg width="40" height="24" viewBox="0 0 40 24" fill="none" class="steparrow">
                              <path d="M0 12H38M38 12L28 2M38 12L28 22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                           </svg>
                        </div>
                        <!-- Step 3 -->
                        <div class="col-lg-4 stepflow">
                           <div class="step-card">
                              <div class="step-num">03</div>
                              <!--<div class="step-icon-wrap">
                                 <svg class="svg-icon" width="35" height="35" style="color:#1fda69">
                                    <use href="#ic-share"></use>
                                 </svg>
                                 </div> -->
                              <div class="step-title">Save &amp; Share Proof</div>
                              <div class="step-detail">Save to gallery or share with embedded timestamp, location, unique ID, and optionally your photo and name as verifiable proof.</div>
                              <div class="step-bottom">
                                 <svg width="200" height="200" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
								  <!-- SAVE (Main Icon) -->
								  <path d="M32 12V36" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
								  <path d="M26 30L32 36L38 30" stroke="#38d762" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								  <rect x="20" y="38" width="24" height="8" rx="3" stroke="#38d762" stroke-width="2"/>
								  <!-- SHARE (Rotated -90° around center 50,24) -->
								  <g transform="rotate(-90 50 24)">
									<!-- Nodes -->
									<circle cx="50" cy="18" r="3" stroke="#38d762" stroke-width="2"/>
									<circle cx="44" cy="28" r="3" stroke="#38d762" stroke-width="2"/>
									<circle cx="56" cy="28" r="3" stroke="#38d762" stroke-width="2"/>
									<!-- Connecting Lines -->
									<path d="M47.5 20.5L45.5 25.5" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
									<path d="M52.5 20.5L54.5 25.5" stroke="#38d762" stroke-width="2" stroke-linecap="round"/>
								  </g>
								</svg>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <div class="third-row wide-60 division">
            <div class="container">
               <div class="row">
                  <h2 class="h2-xs animated w-100 text-center mb-5" data-animation="fadeInLeft" data-animation-delay="300">
                     Key Features
                  </h2>
                  <div class="col-sm-6 col-lg-4 reveal reveal-delay-1 visible mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="feature-card">
                        <div class="feature-icon">
                           <svg class="svg-icon" width="35" height="35" style="color:#1fda69">
                              <use href="#ic-camera-fill"></use>
                           </svg>
                        </div>
                        <div class="feature-title">Secure In-App Capture</div>
                        <div class="feature-desc">All photos must be taken directly within the app. Gallery uploads, screenshots, and external photos are completely blocked to ensure evidence integrity.</div>
                     </div>
                  </div>
                  <div class="col-sm-6 col-lg-4 reveal reveal-delay-2 visible mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="feature-card">
                        <div class="feature-icon">
                           <svg class="svg-icon" width="35" height="35" style="color:#1fda69">
                              <use href="#ic-geo-fill"></use>
                           </svg>
                        </div>
                        <div class="feature-title">Live GPS Metadata</div>
                        <div class="feature-desc">Precise real-time GPS coordinates are captured at the exact moment of the photo. Location data is verified against server records for complete accuracy.</div>
                     </div>
                  </div>
                  <div class="col-sm-6 col-lg-4 reveal reveal-delay-3 visible mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="feature-card">
                        <div class="feature-icon">
                           <svg class="svg-icon" width="35" height="35" style="color:#1fda69">
                              <use href="#ic-clock-fill"></use>
                           </svg>
                        </div>
                        <div class="feature-title">Accurate Time &amp; Timezone</div>
                        <div class="feature-desc">Date, time, and timezone are pulled from secure servers — not the device clock — making time manipulation completely impossible.</div>
                     </div>
                  </div>
                  <div class="col-sm-6 col-lg-4 reveal reveal-delay-1 visible mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="feature-card">
                        <div class="feature-icon">
                           <svg class="svg-icon" width="35" height="35" style="color:#1fda69">
                              <use href="#ic-fingerprint"></use>
                           </svg>
                        </div>
                        <div class="feature-title">Unique Verification ID</div>
                        <div class="feature-desc">Every photo receives a cryptographically unique ID that links back to its immutable metadata record, enabling instant third-party verification.</div>
                     </div>
                  </div>
                  <div class="col-sm-6 col-lg-4 reveal reveal-delay-2 visible mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="feature-card">
                        <div class="feature-icon">
                           <svg class="svg-icon" width="35" height="35" style="color:#1fda69">
                              <use href="#ic-grid-fill"></use>
                           </svg>
                        </div>
                        <div class="feature-title">Professional Templates</div>
                        <div class="feature-desc">Industry-specific metadata display templates for construction, real estate, logistics, banking, insurance, healthcare, and more.</div>
                     </div>
                  </div>
                  <div class="col-sm-6 col-lg-4 reveal reveal-delay-3 visible mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="feature-card">
                        <div class="feature-icon">
                           <svg class="svg-icon" width="35" height="35" style="color:#1fda69">
                              <use href="#ic-sliders"></use>
                           </svg>
                        </div>
                        <div class="feature-title">Customizable Display Controls</div>
                        <div class="feature-desc">Toggle visibility of individual metadata fields directly from Settings.</div>
                        <div class="toggle-list">
                           <span class="toggle-tag">
                              <svg width="16" height="16">
                                 <use href="#ic-toggle"></use>
                              </svg>
                              Name
                           </span>
                           <span class="toggle-tag">
                              <svg width="16" height="16">
                                 <use href="#ic-toggle"></use>
                              </svg>
                              Photo
                           </span>
                           <span class="toggle-tag">
                              <svg width="16" height="16">
                                 <use href="#ic-toggle"></use>
                              </svg>
                              Location
                           </span>
                           <span class="toggle-tag">
                              <svg width="16" height="16">
                                 <use href="#ic-toggle"></use>
                              </svg>
                              Date/Time
                           </span>
                           <span class="toggle-tag">
                              <svg width="16" height="16">
                                 <use href="#ic-toggle"></use>
                              </svg>
                              Timezone
                           </span>
                           <span class="toggle-tag">
                              <svg width="16" height="16">
                                 <use href="#ic-toggle"></use>
                              </svg>
                              Verify ID
                           </span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="second-row bg-lightgrey wide-100 division">
            <div class="container">
               <div class="row d-flex align-items-center">
                  <h2 class="h2-xs animated fadeInLeft visible text-center mb-5 w-100" data-animation="fadeInRight" data-animation-delay="300">
                     How PhotoProof Stops<br/>Common Photo Fraud
                  </h2>
                  <div class="col-md-12">
                     <div class="row g-4 align-items-stretch">
                        <div class="col-md-6 reveal reveal-delay-1 visible">
                           <div class="fraud-col fraud-col-without h-100">
                              <div class="d-flex align-items-center gap-3 mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                                 <div style="width:42px;height:42px; margin-right:20px; border-radius:11px;background:rgba(220,38,38,.1);border:1px solid rgba(220,38,38,.25);display:flex;align-items:center;justify-content:center;">
                                    <svg class="svg-icon" width="35" height="35" style="color:#EF4444">
                                       <use href="#ic-x-circle"></use>
                                    </svg>
                                 </div>
                                 <span style="font-size:20px;font-weight:700;color:#EF4444;">Without PhotoProof</span>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#EF4444;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-x-circle"></use>
                                 </svg>
                                 <div><strong>Reused Old Photos</strong> — Old images passed off as current evidence</div>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#EF4444;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-x-circle"></use>
                                 </svg>
                                 <div><strong>Fake Locations</strong> — GPS coordinates edited or fabricated</div>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#EF4444;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-x-circle"></use>
                                 </svg>
                                 <div><strong>Edited Timestamps</strong> — Date and time easily manipulated</div>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#EF4444;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-x-circle"></use>
                                 </svg>
                                 <div><strong>Offline Manipulation</strong> — No internet required to alter metadata</div>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#EF4444;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-x-circle"></use>
                                 </svg>
                                 <div><strong>No Audit Trail</strong> — Zero traceability of photo origin</div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-6 reveal reveal-delay-2 visible">
                           <div class="fraud-col fraud-col-with h-100">
                              <div class="d-flex align-items-center gap-3 mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                                 <div style="width:42px;height:42px; margin-right:20px; border-radius:11px;background:rgba(0,188,212,.1);border:1px solid rgba(0,188,212,.25);display:flex;align-items:center;justify-content:center;">
                                    <svg class="svg-icon" width="35" height="35" style="color:#1fda69">
                                       <use href="#ic-shield-check-fill"></use>
                                    </svg>
                                 </div>
                                 <span style="font-size:20px;font-weight:700;color:#1fda69;">With PhotoProof</span>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#1fda69;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-check-circle"></use>
                                 </svg>
                                 <div><strong>Live Capture Only</strong> — Photos must be taken in real-time</div>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#1fda69;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-check-circle"></use>
                                 </svg>
                                 <div><strong>GPS Required</strong> — No GPS signal, no photo capture</div>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#1fda69;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-check-circle"></use>
                                 </svg>
                                 <div><strong>Internet Required</strong> — Server-validated before any capture</div>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#1fda69;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-check-circle"></use>
                                 </svg>
                                 <div><strong>Real-Time Metadata</strong> — All data verified from secure servers</div>
                              </div>
                              <div class="fraud-item">
                                 <svg class="svg-icon" width="28" height="28" style="color:#1fda69;margin-top:2px;flex-shrink:0">
                                    <use href="#ic-check-circle"></use>
                                 </svg>
                                 <div><strong>Unique Traceable ID</strong> — Every photo fully traceable</div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="fraud-highlight reveal reveal-delay-3 mt-4 visible">
                        <p style="font-size:16px; letter-spacing:2px;text-transform:uppercase;margin-bottom:10px;">The PhotoProof Rule</p>
                        <div class="fraud-highlight-text">"No Internet. No GPS. No Capture."</div>
                        <p style="font-size:18px;color:var(--text-secondary);margin-top:10px;margin-bottom:0;">Three conditions that guarantee every photo is real, current, and location-verified.</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <section id="team-1" class="bg-fixed bg-lightgrey wide-50 team-section division" style="background-image: url({{ asset('user/images/tra-waves.png')}}); background-repeat:no-repeat;">
            <div class="container">
               <!-- SECTION TITLE -->	
               <div class="row">
                  <div class="col-md-10 offset-md-1 section-title">
                     <!-- Title 	-->	
                     <h2 class="h2-lg">Industries That<br/>Benefit from PhotoProof</h2>
                     <!-- Text -->
                     <p>PhotoProof is ideal for sectors where visual evidence must be trusted:
                     </p>
                  </div>
                  <!-- End row --> 	
               </div>
               <!-- END SECTION TITLE -->	
               <div class="row g-4 justify-content-center">
                  <div class="col-6 col-md-4 col-lg-4 reveal reveal-delay-1 visible  mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="industry-card" >
                        <div class="industry-icon-svg"><svg class="svg-icon" width="35" height="35" style="color:#38d762"><use href="#ic-construction"></use></svg></div>
                        <div class="industry-name">Construction &amp; Infrastructure</div>
                        <div class="industry-desc">Site progress, compliance, and safety documentation.</div>
                     </div>
                  </div>
                  <div class="col-6 col-md-4 col-lg-4 reveal reveal-delay-2 visible  mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="industry-card">
                        <div class="industry-icon-svg"><svg class="svg-icon" width="35" height="35" style="color:#38d762"><use href="#ic-home"/></svg></div>
                        <div class="industry-name">Real Estate &amp; Property</div>
                        <div class="industry-desc">Verified property condition reports and inspections.</div>
                     </div>
                  </div>
                  <div class="col-6 col-md-4 col-lg-4 reveal reveal-delay-3 visible  mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="industry-card">
                        <div class="industry-icon-svg"><svg class="svg-icon" width="35" height="35" style="color:#38d762"><use href="#ic-truck"/></svg></div>
                        <div class="industry-name">Logistics &amp; Supply Chain</div>
                        <div class="industry-desc">Proof of delivery, pickup, and cargo condition.</div>
                     </div>
                  </div>
                  <div class="col-6 col-md-4 col-lg-4 reveal reveal-delay-1 visible  mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="industry-card">
                        <div class="industry-icon-svg"><svg class="svg-icon" width="35" height="35" style="color:#38d762"><use href="#ic-banknote"/></svg></div>
                        <div class="industry-name">Banking &amp; Financial</div>
                        <div class="industry-desc">KYC documentation and field verification.</div>
                     </div>
                  </div>
                  <div class="col-6 col-md-4 col-lg-4 reveal reveal-delay-2 visible  mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="industry-card">
                        <div class="industry-icon-svg"><svg class="svg-icon" width="35" height="35" style="color:#38d762"><use href="#ic-shield"/></svg></div>
                        <div class="industry-name">Insurance</div>
                        <div class="industry-desc">Verified claims, damage assessment, and loss documentation.</div>
                     </div>
                  </div>
                  <div class="col-6 col-md-4 col-lg-4 reveal reveal-delay-3 visible  mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="industry-card">
                        <div class="industry-icon-svg"><svg class="svg-icon" width="35" height="35" style="color:#38d762"><use href="#ic-factory"/></svg></div>
                        <div class="industry-name">Manufacturing &amp; QC</div>
                        <div class="industry-desc">Product quality control and production audits.</div>
                     </div>
                  </div>
                  <div class="col-6 col-md-4 col-lg-4 reveal reveal-delay-1 visible  mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="industry-card">
                        <div class="industry-icon-svg"><svg class="svg-icon" width="35" height="35" style="color:#38d762"><use href="#ic-heart-pulse"/></svg></div>
                        <div class="industry-name">Healthcare &amp; Field Services</div>
                        <div class="industry-desc">Field visit verification and patient care documentation.</div>
                     </div>
                  </div>
                  <div class="col-6 col-md-4 col-lg-4 reveal reveal-delay-2 visible  mb-3 animated" data-animation="fadeInRight" data-animation-delay="400">
                     <div class="industry-card">
                        <div class="industry-icon-svg"><svg class="svg-icon" width="35" height="35" style="color:#38d762"><use href="#ic-landmark"/></svg></div>
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
                        <h4 class="h4-md text-white"><span>Why Organizations Choose PhotoProof?</span></h4>
                        <!-- Text -->	
                        <p>In a world where evidence can be fabricated, organizations need a solution they can trust unconditionally. PhotoProof delivers enterprise-grade documentation that stands up to legal scrutiny, audit requirements, and regulatory compliance.
                        </p>
                     </div>
                  </div>
               </div>
               <!-- END JOIN TEAM BUTTON -->
            </div>
         </section>
 @endsection