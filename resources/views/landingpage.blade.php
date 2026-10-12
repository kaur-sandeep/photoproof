<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>PhotoProof Verified – Smart Delivery Verification</title>
  
    <link rel="shortcut icon" href="https://photoproof.cogniter.com/user/images/favicon.ico" type="image/x-icon">
      <link rel="icon" href="https://photoproof.cogniter.com/user/images/favicon.ico" type="image/x-icon">
      <link rel="apple-touch-icon" sizes="152x152" href="https://photoproof.cogniter.com/user/images/apple-touch-icon-152x152.png">
      <link rel="apple-touch-icon" sizes="120x120" href="https://photoproof.cogniter.com/user/images/apple-touch-icon-120x120.png">
      <link rel="apple-touch-icon" sizes="76x76" href="https://photoproof.cogniter.com/user/images/apple-touch-icon-76x76.png">
      <link rel="apple-touch-icon" href="https://photoproof.cogniter.com/user/images/apple-touch-icon.png">

  <!-- Bootstrap 4.0.0 -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <!-- Font Awesome 4.7 (icons) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Merriweather:ital,opsz,wght@0,18..144,300..900;1,18..144,300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Teko:wght@300..700&display=swap" rel="stylesheet">

  <meta name="google-site-verification" content="dGNmFfl9eeNw1odlVun4cS-a70rnreY1hhQiuUtkW8E" />
  <meta name="description" content="Eliminate photo fraud! Get secure, source-verified images with live GPS & timestamps. No more gallery uploads! Perfect for outlet visits, rental inspections, and audits. Download now!">
  <meta name="keywords" content="Photoproof, image verification tool, secure photo capture, digital proof app, GPS metadata camera, prevent image manipulation, authentic photos, digital evidence integrity.">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://photoproof.cogniter.com/">
  <meta property="og:title" content="Photoproof | 100% Tamper-Proof GPS Camera & Image Validation">
  <meta property="og:description" content="Eliminate photo fraud! Get secure, source-verified images with live GPS & timestamps. No more gallery uploads! Perfect for outlet visits, rental inspections, and audits. Download now!">
  <meta property="og:image" content="https://photoproof.cogniter.com/user/images/og-image.png">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  

  <style>
    :root {
      --pp-green: #1cc45a;
      --pp-green-dark: #13a548;
      --pp-blue: #1e88e5;
      --pp-navy: #111827;
      --pp-text: #374151;
      --pp-muted: #6b7280;
      --pp-border: #e5ebf2;
      --pp-bg-soft: #f2faff;
      --pp-red: #e53935;
      --pp-red-soft: #fff4f4;
    }

    body {
      font-family: 'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
      color: var(--pp-text);
      font-size: 15px;
      line-height: 1.6;
      background: #fff;
    }
    h1, h2, h3, h4, h5, .font-heading {
      font-family: 'Montserrat', sans-serif;
      color: var(--pp-navy);
      font-weight: 600;
    }
    a { color: var(--pp-blue); }
    a:hover { color: #1565c0; text-decoration: none; }
    img { max-width: 100%; }

    .section { padding: 36px 0; }
    .section-label {
      font-size: 12px;
      font-weight: 600;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: var(--pp-blue);
      margin-bottom: 5px;
    }
    .section-title { font-size: 32px; margin-bottom: 0; }
    .text-pp-green { color: var(--pp-green); }
    h5 .text-pp-green { color: #fff; background: var(--pp-blue); border-radius: 5px; padding: 2px 5px; }

    /* ---------- Buttons ---------- */
    .btn-pp {
      background: var(--pp-green);
      border: 1px solid var(--pp-green);
      color: #fff;
      font-weight: 600;
      font-size: 14px;
      border-radius: 6px;
      padding: 12px 22px;
    }
    .btn-pp:hover, .btn-pp:focus { background: var(--pp-green-dark); border-color: var(--pp-green-dark); color: #fff; }
    .btn-pp-sm { padding: 6px 14px; font-size: 13px; }
    .btn:focus, .form-control:focus { box-shadow: 0 0 0 3px rgba(28,196,90,.3); }

    @media (min-width: 1200px) {
      .container { max-width: 1300px; }
    }

    /* ---------- Logo ---------- */
    .pp-logo { display: inline-flex; align-items: center; text-decoration: none !important; }
    .pp-logo-icon {
      width: 46px; height: 46px; border-radius: 10px;
      background: linear-gradient(135deg, var(--pp-green) 50%, var(--pp-blue) 50%);
      display: flex; align-items: center; justify-content: center; margin-right: 10px;
    }
    .pp-logo-icon span {
      width: 28px; height: 28px; border-radius: 50%;
      background: var(--pp-navy); border: 3px solid #fff;
      display: flex; align-items: center; justify-content: center;
      color: var(--pp-green); font-size: 12px;
    }
    .pp-logo-text { line-height: 1; }
    .pp-logo-text strong {
      display: block; font-family: 'Roboto', 'Helvetica Neue', Helvetica, Arial, sans-serif;
      font-size: 28px; font-weight: 600; color: var(--pp-navy); letter-spacing: .5px;
    }
    .pp-logo-text strong em { font-style: normal; color: var(--pp-green); }
    .pp-logo-text small {
      display: block; font-size: 11px; letter-spacing: .85em; color: var(--pp-navy); margin-top: 4px;
    }
    .pp-logo.pp-logo-sm .pp-logo-icon { width: 36px; height: 36px; }
    .pp-logo.pp-logo-sm .pp-logo-icon span { width: 22px; height: 22px; font-size: 10px; }
    .pp-logo.pp-logo-sm .pp-logo-text strong { font-size: 22px; }
    .pp-logo.pp-logo-sm .pp-logo-text small { font-size: 9px; }

    /* ---------- Navbar ---------- */
    .pp-navbar { padding: 10px 0; background: #fff; }
    .pp-navbar .nav-link { color: var(--pp-navy); font-size: 14px; font-weight: 500; padding: 8px 14px !important; }
    .pp-navbar .nav-link:hover { color: var(--pp-green); }
    .navbar-light .navbar-brand img { width: 213px; }

    /* ---------- Hero ---------- */
    .hero {
      background: #f5fbfe;
      position: relative; overflow: hidden; padding: 0px 0 0;
    }
    .hero h1 { font-size: 46px; line-height: 1.12; margin-bottom: 22px; }
    .hero-lead { font-size: 16px; color: var(--pp-text); margin-bottom: 20px; }
    .hero-form .input-wrap { position: relative; flex: 1; margin-right: 12px; }
    .hero-form .input-wrap .fa { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--pp-navy); }
    .hero-form .form-control {
      height: 48px; padding-left: 46px; border: 1px solid var(--pp-border);
      border-radius: 6px; font-size: 13px; background: #fff;
    }
    .hero-form .btn-pp { height: 48px; white-space: nowrap; }
    .hero-note { font-size: 13px; color: #0a8bcf; margin-top: 14px; }

    .hero-visual { position: relative; min-height: 430px; }
    .hero-visual:after {
      position: absolute; right: -5%; bottom: -10%; border-radius: 50%; width: 10rem; height: 10rem; content: ""; background: rgba(28,196,90,.5);
    }
    .hero-visual .hero-img { position: absolute; bottom: 0; left: 0; width: 100%; object-fit: cover; object-position: left center; background: #dde8f2; border-radius: 0; height: 100%; }
    .hero-badges { position: absolute; left: 387px; top: 50px; width: 220px; z-index: 2; line-height: 1.2; }
    .hero-badge {
      background: #fff; border-radius: 8px; padding: 12px;
      display: flex; align-items: center; margin-bottom: 6px;
      box-shadow: 0 8px 24px rgba(17,24,39,.08);
    }
    .hero-badge .ico {
      width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center; color: #fff; margin-right: 10px;
    }
    .hero-badge strong { display: block; font-size: 12.5px; color: var(--pp-navy); letter-spacing: .03em; }
    .hero-badge span { font-size: 11px; color: var(--pp-muted); }
    .hero-proof {
      position: absolute; left: 469px; bottom: 47px; width: 140px; z-index: 2;
      border: 3px solid #fff; border-radius: 8px; box-shadow: 0 8px 24px rgba(17,24,39,.12);
    }
    .hero-proof img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
      background: #c9b79c;
      border-radius: 5px;
    }
    .hero-proof .check {
      position: absolute; top: -10px; right: -10px; width: 22px; height: 22px; border-radius: 50%;
      background: var(--pp-green); color: #fff; font-size: 11px;
      display: flex; align-items: center; justify-content: center; border: 2px solid #fff;
    }
    .cta-box img { width: 60px; }

    .bg-green { background: var(--pp-green); }
    .bg-blue { background: var(--pp-blue); }

    /* ---------- Features ---------- */
    .features { background: #ecf8ff; }
    .feature-card {
      background: #fff; border: 1px solid var(--pp-border); border-radius: 8px;
      padding: 28px 22px; height: 100%;
    }
    .feature-icon {
      width: 52px; height: 52px; border-radius: 50%; color: #fff; font-size: 20px;
      display: flex; align-items: center; justify-content: center; margin-bottom: 20px;
    }
    .feature-card h3 { font-size: 20px; line-height: 1.35; margin-bottom: 14px; }
    .feature-card p { font-size: 16px; color: var(--pp-muted); margin: 0; line-height: 1.4; }

    /* ---------- How it works ---------- */
    .how { padding: 0; background: #fff; }
    .how-content { padding: 30px 0 50px; }
    .step { padding-right: 20px; }
    .step + .step { border-left: 1px solid var(--pp-border); padding-left: 20px; }
    .step-head { display: flex; align-items: center; margin-bottom: 18px; }
    .step-num {
      width: 28px; height: 28px; border-radius: 50%; color: #fff; font-size: 13px; font-weight: 600;
      display: flex; align-items: center; justify-content: center; margin-right: 18px; background: #6a6a6a;
    }
    .step-head .fa { font-size: 26px; }
    .step h4 { font-size: 20px; margin-bottom: 8px; }
    .step p { font-size: 14px; color: var(--pp-muted); margin: 0; line-height: 1.6; }
    .how-visual { position: relative; min-height: 360px; height: 100%; background: #dce7ef; overflow: hidden; }
    .how-visual > img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; object-position: left; }

    /* ---------- Comparison ---------- */
    .compare { background: #f6fafe; }
    .compare-card { border-radius: 8px; padding: 18px 24px; height: 100%; display: flex; align-items: center; }
    .compare-card.old { background: var(--pp-red-soft); border: 1px solid #f6caca; }
    .compare-card.new { background: #03bb750f; border: 1px solid #03bb758f; }
    .compare-card h4 { font-size: 20px; display: flex; align-items: center; margin-bottom: 14px; }
    .compare-card.old h4 { color: var(--pp-red); }
    .compare-card.new h4 { color: var(--pp-green); }
    .compare-card h4 .fa {
      width: 26px; height: 26px; border-radius: 50%; color: #fff; font-size: 13px;
      display: inline-flex; align-items: center; justify-content: center; margin-right: 10px;
    }
    .comparewrapper { padding: 0 30px; }
    .compare-list { list-style: none; padding: 0; margin: 0; font-size: 14px; }
    .compare-list li { display: flex; align-items: center; margin-bottom: 8px; }
    .compare-list li .fa { width: 22px; flex-shrink: 0; }
    .compare-card.old .compare-list .fa { color: var(--pp-red); }
    .compare-card.new .compare-list .fa { color: var(--pp-green); }
    .compare-phone {
      flex-shrink: 0; width: 103px; overflow: hidden;
      position: relative;
    }
    .compare-phone img { width: 100%; height: 100%; object-fit: cover; }
    .compare-phone .flag {
      position: absolute; right: -2px; bottom: -2px; width: 28px; height: 28px; border-radius: 50%;
      background: var(--pp-red); color: #fff; display: flex; align-items: center; justify-content: center;
      border: 2px solid #fff;
    }

    /* ---------- CTA ---------- */
    .cta-wrap { padding: 30px 0 30px; }
    .cta-box {
      background: #f3fbfe;
      border: 1px solid #f5f5f5; border-radius: 10px; padding: 26px 40px;
      position: relative;
      overflow: hidden;
    }
    .cta-box::before {
      content: "";
      position: absolute;
      width: 263px;
      height: 198px;
      right: -111px;
      bottom: -73px;
      background: #d1efff;
      border-radius: 50%;
      transform: rotate(-42deg);
    }
    .cta-box::after {
      content: "";
      position: absolute;
      width: 500px;
      height: 20px;
      left: -315px;
      background: #ddf8ef;
      border-radius: 0;
      transform: rotate(-42deg);
    }
    .cta-shield { font-size: 54px; color: var(--pp-green); line-height: 1; }
    .cta-box .section-label { color: var(--pp-green-dark); font-size: 10.5px; margin-bottom: 4px; }
    .cta-box h3 { font-size: 24px; margin-bottom: 6px; }
    .cta-box p { font-size: 14px; margin: 0; }
    .cta-box .btn-pp { min-width: 190px; }
    .cta-box .demo-link { display: block; font-size: 12.5px; font-weight: 500; margin-top: 10px; }

    /* ---------- Footer ---------- */
    .pp-footer { padding: 20px 0 20px; }
    .footer-nav a { color: var(--pp-muted); font-size: 12.5px; margin: 0 12px; }
    .footer-nav a:hover { color: var(--pp-green); }
    .footer-social { border-left: 1px solid var(--pp-border); padding-left: 30px; }
    .footer-social a { color: #4b5d73; font-size: 18px; margin-left: 18px; }
    .footer-social a:hover { color: var(--pp-green); }
    .pp-logo.pp-logo-sm img { width: 192px; }
    .os-version {
      padding-top: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .os-version span {
      display: flex;
      justify-content: center;
      align-items: center; padding: 0px 10px;
    }
    .copyright { text-align: center; }
    .copyright img { max-width: 150px; }
    footer svg { width: 18px; float: left; margin-top: 3px; }
    .hero h1 { font-weight: bold; }

    .hero-offer {
      position: absolute; top: 4%; left: -4%; border-radius: 10px; z-index: 1; font-family: "Bebas Neue", sans-serif;
      background: var(--pp-navy); display: flex; flex-direction: column;
    }
    .hero-offer .price { font-size: 3.25rem; color: #fff900; padding: 10px 10px 0px 10px; line-height: normal; overflow: hidden; border-radius: 10px; }
    .hero-offer .price font { font-size: 1.25rem; color: #fff; }
    .free_photo { background: var(--pp-blue); color: #fff; padding: 3px 0px; text-align: center; font-size: 1.25rem; border-radius: 0px 0px 10px 10px; }
    .mobile-hero-offer { display: none !important; }

    /* ---------- Popup (modal) ---------- */
    .pp-modal .modal-content { border: 0; border-radius: 12px; box-shadow: 0 20px 50px rgba(17,24,39,.25); }
    .pp-modal .modal-body { padding: 32px 28px 24px; text-align: center; }
    .pp-modal-icon {
      width: 64px; height: 64px; border-radius: 50%; margin: 0 auto 16px;
      display: flex; align-items: center; justify-content: center; font-size: 30px; color: #fff;
    }
    .pp-modal-icon.error   { background: var(--pp-red); }
    .pp-modal-icon.success { background: var(--pp-green); }
    .pp-modal h5 { font-size: 20px; margin-bottom: 10px; }
    .pp-modal-list { list-style: none; padding: 0; margin: 0 0 20px; font-size: 14px; color: var(--pp-text); }
    .pp-modal-list li { margin-bottom: 6px; }
    .pp-modal .btn-pp { min-width: 120px; }

    /* ---------- Responsive ---------- */
    @media (max-width: 991.98px) {
      .hero-offer { display: none; }
      .mobile-hero-offer {
        font-size: 1.25rem; color: #fff; padding: 10px; line-height: normal; overflow: hidden; border-radius: 0px;
        background: #00a8ff; font-weight: bold; text-align: center; display: block !important;
      }
      .mobile-hero-offer span { color: #fff900; }
      .hero { padding-top: 0px !important; }
    }
    @media (max-width: 1280px) {
      .container { max-width: 96%; }
      .hero-badges { left: 365px; }
      .hero-proof { left: 446px; }
      .how-visual > img { object-position: left; }
    }
    @media (max-width: 1199px) {
      .hero h1 { font-size: 34px; }
      .hero-badges { left: auto; right: 14px; }
      .hero-proof { left: auto; right: 16px; }
      .hero-visual { position: relative; min-height: 406px; }
      .feature-card h3 { font-size: 16px; }
      .feature-card p { font-size: 14px; }
    }
    @media (max-width: 991.98px) {
      .hero .pt-3.pb-3.w-100 {
        padding-left: 30px !important;
        padding-right: 30px;
      }
      .hero-badges { top: 26px; }
      .col-lg-4.step { width: 33.3333%; }
      .how-content { padding: 29px 30px 44px !important; }
      .comparewrapper { padding: 0 9px; }
      .hero h1 { font-size: 36px; }
      .hero-visual { margin-top: 0px; min-height: 380px; }
      .step + .step { border-left: 0; padding-left: 0; }
      .step { margin-bottom: 26px; }
      .how-visual { min-height: 320px; }
      .footer-social { border-left: 0; padding-left: 0; }
    }
    @media (max-width: 767.98px) {
      .hero .pt-3.pb-3.w-100 {
        padding-left: 15px !important;
        padding-right: 15px;
      }
      .section { padding: 56px 0; }
      .section-title { font-size: 26px; }
      .hero { padding-top: 14px; }
      .hero h1 { font-size: 30px; }
      .hero-form { flex-direction: column; }
      .hero-form .input-wrap { margin: 0 0 10px; }
      .hero-visual .hero-img {
        width: 100%;
        object-position: -60px;
      }
      .hero-badge strong { font-size: 10.5px; }
      .how-content .section-label { text-align: center; }
      .how-content .section-title { text-align: center; }
      .navbar-expand-lg>.container, .navbar-expand-lg>.container-fluid {
        padding-right: 15px;
        padding-left: 14px;
      }
      .step-head {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        justify-content: center;
      }
      .step + .step {
        border-left: 0;
        padding-left: 20px;
      }
      .col-lg-4.step {
        width: 100%;
        text-align: center;
      }
      .hero-badge .ico { width: 26px; height: 26px; }
      .hero-badge { padding: 11px; }
      .hero-badges { width: 163px; top: 16px; }
      .how-content { padding: 29px 20px 27px !important; }
      .step-num { display: none; }
      .container { max-width: 100%; }
      .compare-card {
        flex-direction: column;
        align-items: center;
      }
      .compare-card.new {
        flex-direction: column-reverse;
        align-items: center;
      }
      .compare-phone { margin-bottom: 16px; }
      .cta-box { padding: 24px; text-align: center; }
      .cta-shield { margin-bottom: 12px; }
      .footer-nav a { margin: 0 8px; }
    }
  </style>
      <!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-WHTW5BS');</script>
	<!-- End Google Tag Manager -->
</head>
<body>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WHTW5BS"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- ================= NAVBAR ================= -->
  <nav class="navbar navbar-expand-lg navbar-light pp-navbar">
    <div class="container">
      <a class="pp-logo navbar-brand" href="#">
        <img src="{{ asset('user/images/logo.png') }}" alt="logo"/>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav"
              aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item nl-simple"><a class="nav-link" href="/terms-conditions">Terms of Service</a></li>
          <li class="nav-item nl-simple"><a class="nav-link" href="/privacy-policy">Privacy Policy</a></li>
          <li class="nav-item nl-simple"><a class="nav-link" href="https://photoproof.cogniter.com#contact-us">Contact us</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- ================= HERO ================= -->
  <header class="hero">
    <div class="container-fluid px-0">
      <div class="row align-items-center">
        <div class="col-lg-6 ">
          <div class="mobile-hero-offer">
            <span class="">$0.00<font>/MO</font></span> FREE 100 Photos/MO
          </div>
          <div class="pt-3 pb-3 w-100" style="padding-left: max(45px, calc((100vw - 1280px) / 2)); ">
            <div class="section-label">Logistics &amp; Delivery Verification</div>

            <h1>Smart Delivery Verification for Modern <span class="text-pp-green">Logistics Fleets</span></h1>
            <p class="hero-lead">
              PhotoProof locks delivery photo capture to real-time GPS and server-synced timestamps — no gallery uploads, no spoofed locations, no exceptions.
            </p>

            <!-- novalidate: browser tooltip nahi aayega, validation server karega aur error popup mein dikhega -->
            <form class="hero-form d-flex" action="{{ route('organization.create') }}" method="POST" novalidate>
              @csrf
              @if($selectedPlan)
                <input type="hidden" name="subscription_plan" value="{{ $selectedPlan->id }}">
                <input type="hidden" name="billing_cycle" value="{{ $billingCycle }}">
              @endif
              <div class="input-wrap">
                <i class="fa fa-envelope-o" aria-hidden="true"></i>
                <label for="heroEmail" class="sr-only">Work email</label>
                <input type="email" class="form-control" id="heroEmail" name="organization_email"
                       value="{{ old('organization_email') }}"
                       placeholder="Enter your work email" autocomplete="email">
              </div>
              <button type="submit" class="btn btn-pp">Create Your Account <i class="fa fa-arrow-right ml-1"></i></button>
            </form>
            <p class="hero-note" style="font-weight:bold;">Get started in minutes. No credit card required. FREE 100 Photos/MO.</p>
          </div>
        </div>

        <div class="col-lg-6 align-self-end">
          <div class="hero-visual">
            <div class="hero-offer">
              <span class="price">$0.00<font>/MO</font></span>
              <span class="free_photo">FREE 100 Photos/MO</span>
            </div>
            <img class="hero-img" src="{{ asset('user/landingpage/banner-right.jpg') }}" alt="Delivery driver confirming a package drop-off on his phone">

            <div class="hero-badges">
              <div class="hero-badge">
                <div class="ico bg-green"><i class="fa fa-map-marker"></i></div>
                <div><strong>GPS VERIFIED</strong><span>Location confirmed</span></div>
              </div>
              <div class="hero-badge">
                <div class="ico bg-blue"><i class="fa fa-clock-o"></i></div>
                <div><strong>SERVER TIMESTAMP</strong><span>Real-time capture</span></div>
              </div>
              <div class="hero-badge">
                <div class="ico bg-blue"><i class="fa fa-camera"></i></div>
                <div><strong>PHOTO VERIFIED</strong><span>Authentic and unedited</span></div>
              </div>
            </div>

            <div class="hero-proof">
              <img src="{{ asset('user/landingpage/how-it-work.jpg') }}" alt="Package left at a front door">
              <div class="check"><i class="fa fa-check"></i></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- ================= FEATURES ================= -->
  <section class="section features" id="features">
    <div class="container">
      <div class="text-center mb-4">
        <div class="section-label">Key Features</div>
        <h2 class="section-title">Everything You Need for Verified Deliveries</h2>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
          <div class="feature-card">
            <div class="feature-icon bg-green"><i class="fa fa-camera"></i></div>
            <h3>Live POD Capture &amp; Asset Management</h3>
            <p>Capture delivery photos and manage assets in one secure, easy-to-use platform.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
          <div class="feature-card">
            <div class="feature-icon bg-blue"><i class="fa fa-map-marker"></i></div>
            <h3>Geofence &amp; Location-Verified Drop-Offs</h3>
            <p>Ensure deliveries happen at the right place, at the right time — every time.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-4 mb-md-0">
          <div class="feature-card">
            <div class="feature-icon bg-green"><i class="fa fa-users"></i></div>
            <h3>Fleet &amp; Dispatch Collaboration</h3>
            <p>Keep your drivers, dispatch and operations teams connected and in sync.</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="feature-card">
            <div class="feature-icon bg-blue"><i class="fa fa-shield"></i></div>
            <h3>Secure, Audit-Ready Chain of Custody</h3>
            <p>Maintain a tamper-proof record with time, location and photo verification.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= HOW IT WORKS ================= -->
  <section class="how" id="how-it-works">
    <div class="container-fluid px-0">
      <div class="row no-gutters">
        <div class="col-lg-7 d-flex align-items-center">
          <div class="how-content w-100" style="padding-left: max(45px, calc((100vw - 1280px) / 2)); padding-right: 30px;">
            <div class="section-label">How Photo Proof Works</div>
            <h2 class="section-title mb-5">Get Started in 3 Simple Steps</h2>
            <div class="row">
              <div class="col-lg-4 step">
                <div class="step-head">
                  <div class="step-num">1</div>
                </div>
                <h4>Set Up Your Company</h4>
                <p>Create your account, configure your fleet and settings in minutes.</p>
              </div>
              <div class="col-lg-4 step">
                <div class="step-head">
                  <div class="step-num">2</div>
                </div>
                <h4>Invite Your Employees</h4>
                <p>Add drivers, dispatchers and team members to your account.</p>
              </div>
              <div class="col-lg-4 step">
                <div class="step-head">
                  <div class="step-num">3</div>
                </div>
                <h4>Start Capturing Photos</h4>
                <p>Your team can now capture verified delivery photos with location and timestamp.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="how-visual">
            <img src="{{ asset('user/landingpage/how-it-work.jpg') }}" alt="Phone showing a confirmed delivery with GPS, time and photo verified">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= COMPARISON ================= -->
  <section class="section compare">
    <div class="container">
      <div class="text-center mb-4">
        <div class="section-label">Stop Fraud. Protect Your Business.</div>
        <h2 class="section-title">Real Verification. Not Just a Photo.</h2>
      </div>
      <div class="comparewrapper">
        <div class="row">
          <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="compare-card old">
              <div class="compare-phone mr-4">
                <img src="{{ asset('user/landingpage/oldway.png') }}" alt="Phone photo gallery">
              </div>
              <div>
                <h4><i class="fa fa-times" style="background: var(--pp-red);"></i>The Old Way – Easy to Fake</h4>
                <ul class="compare-list">
                  <li><i class="fa fa-times"></i>Photos from gallery uploads</li>
                  <li><i class="fa fa-times"></i>Fake or spoofed GPS locations</li>
                  <li><i class="fa fa-times"></i>Incorrect or manipulated timestamps</li>
                  <li><i class="fa fa-times"></i>No real proof of delivery</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="compare-card new justify-content-between">
              <div>
                <h4><i class="fa fa-check" style="background: var(--pp-green);"></i>PhotoProof – Fully Verified</h4>
                <ul class="compare-list">
                  <li><i class="fa fa-check"></i>Live photo capture (no gallery uploads)</li>
                  <li><i class="fa fa-check"></i>Real-time GPS location</li>
                  <li><i class="fa fa-check"></i>Server-synced timestamp</li>
                  <li><i class="fa fa-check"></i>Tamper-proof, audit-ready records</li>
                </ul>
              </div>
              <div class="compare-phone ml-3">
                <img src="{{ asset('user/landingpage/fully-verified.png') }}" alt="PhotoProof app showing GPS, time and photo verified">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= CTA ================= -->
  <section class="cta-wrap">
    <div class="container">
      <div class="cta-box">
        <div class="row align-items-center">
          <div class="col-md-auto text-center">
            <img src="{{ asset('user/landingpage/trustedicon.png') }}" alt="trustedicon"/>
          </div>
          <div class="col-md">
            <div class="section-label">Trusted by Logistics Teams Nationwide</div>
            <h3>No Internet. No GPS. No Capture.</h3>
            <p>That's the PhotoProof difference. Reliable. Secure. Always on.</p>
          </div>
          <div class="col-md-auto text-center mt-4 mt-md-0">
            <a href="#" class="btn btn-pp">Create Your Account <i class="fa fa-arrow-right ml-1"></i></a>
            <a href="https://photoproof.cogniter.com/#contact-us" target="_blank" class="demo-link">Book a Demo</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= FOOTER ================= -->
  <footer class="second-row bg-lightgrey wide-50 division">
    <div class="container">
      <div class="row d-flex align-items-center mb-4">
        <h2 class="h2-xs animated fadeInLeft visible text-center mb-3 w-100" data-animation="fadeInRight" data-animation-delay="300">
          Don't Just Take Photos.<br><span style="color: var(--pp-green-dark);">Capture Proof.</span>
        </h2>
        <p class="text-center w-100 mb-4">With Photo Proof, every image becomes verified documentation backed by live metadata and secure validation.<br>Download Photo Proof today and ensure your visual records are trusted, traceable, and tamper-resistant.</p>
        <div class="animated fadeInUp visible w-100 text-center" data-animation="fadeInUp" data-animation-delay="500">
          <a href="https://apps.apple.com/us/app/photo-proof/id6759238577" class="store" target="_blank" rel="noopener noreferrer">
            <img class="appstore-white" src="https://photoproof.cogniter.com/user/images/store_badges/appstore.png" width="155" height="50" alt="Download PhotoProof on the App Store">
          </a>

          <a href="https://play.google.com/store/apps/details?id=com.photoproof" class="store" target="_blank" rel="noopener noreferrer">
            <img class="googleplay-white" src="https://photoproof.cogniter.com/user/images/store_badges/googleplay.png" width="164" height="50" alt="Get PhotoProof on Google Play">
          </a>
          <div class="os-version">
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shield-shaded" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 14.933a1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
              </svg>
              End-to-end encrypted
            </span>
            |
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wifi-off" viewBox="0 0 16 16">
                <path d="M10.706 3.294A12.6 12.6 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.048.736.52.52 0 0 0 .668.05A11.45 11.45 0 0 1 8 4q.946 0 1.852.148zM8 6c-1.905 0-3.68.56-5.166 1.526a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065 8.45 8.45 0 0 1 3.51-1.27zm2.596 1.404.785-.785q.947.362 1.785.907a.482.482 0 0 1 .063.745.525.525 0 0 1-.652.065 8.5 8.5 0 0 0-1.98-.932zM8 10l.933-.933a6.5 6.5 0 0 1 2.013.637c.285.145.326.524.1.75l-.015.015a.53.53 0 0 1-.611.09A5.5 5.5 0 0 0 8 10m4.905-4.905.747-.747q.886.451 1.685 1.03a.485.485 0 0 1 .047.737.52.52 0 0 1-.668.05 11.5 11.5 0 0 0-1.811-1.07M9.02 11.78c.238.14.236.464.04.66l-.707.706a.5.5 0 0 1-.707 0l-.707-.707c-.195-.195-.197-.518.04-.66A2 2 0 0 1 8 11.5c.374 0 .723.102 1.021.28zm4.355-9.905a.53.53 0 0 1 .75.75l-10.75 10.75a.53.53 0 0 1-.75-.75z"/>
              </svg>
              No offline capture
            </span>
            |
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M256 160L256 224L384 224L384 160C384 124.7 355.3 96 320 96C284.7 96 256 124.7 256 160zM192 224L192 160C192 89.3 249.3 32 320 32C390.7 32 448 89.3 448 160L448 224C483.3 224 512 252.7 512 288L512 512C512 547.3 483.3 576 448 576L192 576C156.7 576 128 547.3 128 512L128 288C128 252.7 156.7 224 192 224z"/></svg>
              Tamper-proof metadata
            </span>
          </div>
        </div>
      </div>
      <hr>
      <div class="col-md-12">
        <div class="copyright">
          <img src="{{ asset('user/landingpage/footer-logo-new.png') }}">
          <p>© 2026 Photo Proof. All Rights Reserved. <a href="/terms-conditions">Terms of Service</a> | <a href="/privacy-policy">Privacy Policy</a></p>
        </div>
      </div>
    </div>
  </footer>

  <!-- ================= MESSAGE POPUP ================= -->
  <div class="modal fade pp-modal" id="ppMessageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="pp-modal-icon" id="ppModalIcon"><i class="fa" id="ppModalIconI"></i></div>
          <h5 id="ppModalTitle"></h5>
          <ul class="pp-modal-list" id="ppModalList"></ul>
          <button type="button" class="btn btn-pp" data-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap 4.0.0 JS (jQuery must load first, then Popper, then Bootstrap) -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

  <!-- Error / Success popup (server messages) -->
  <script>
    (function () {
      var $ = window.jQuery;

      function showPopup(type, messages) {
        var isError = type === 'error';

        document.getElementById('ppModalIcon').className  = 'pp-modal-icon ' + (isError ? 'error' : 'success');
        document.getElementById('ppModalIconI').className = 'fa ' + (isError ? 'fa-times' : 'fa-check');
        // document.getElementById('ppModalTitle').textContent = isError ? 'Oops! Please fix this' : 'Success';
        document.getElementById('ppModalTitle').textContent = isError ? 'Oops!' : 'Success';

        var list = document.getElementById('ppModalList');
        list.innerHTML = '';
        messages.forEach(function (msg) {
          var li = document.createElement('li');
          li.textContent = msg; // textContent => XSS safe
          list.appendChild(li);
        });

        $('#ppMessageModal').modal('show');
      }

      var serverErrors  = @json($errors->all());
      var serverError   = @json(session('error'));
      var serverSuccess = @json(session('success'));

      document.addEventListener('DOMContentLoaded', function () {
        var errs = serverErrors.slice();
        if (serverError) errs.push(serverError);

        if (errs.length) {
          showPopup('error', errs);
        } else if (serverSuccess) {
          showPopup('success', [serverSuccess]);
        }
      });
    })();
  </script>

  <!-- Fallback: keeps the mobile menu working even if Bootstrap's JS fails to load -->
  <script>
    (function () {
      if (window.jQuery && window.jQuery.fn && window.jQuery.fn.collapse) return;
      var toggler = document.querySelector('.navbar-toggler');
      var menu = document.getElementById('mainNav');
      if (!toggler || !menu) return;
      toggler.addEventListener('click', function () {
        var open = menu.classList.toggle('show');
        toggler.classList.toggle('collapsed', !open);
        toggler.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
    })();
  </script>
</body>
</html>