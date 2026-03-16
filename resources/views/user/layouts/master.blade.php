<!DOCTYPE html>
<html lang="en">
<head>
    @include('user.partials.head')
    <!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-WHTW5BS');</script>
	<!-- End Google Tag Manager -->
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WHTW5BS"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div class="app-wrapper">
     @include('user.partials.svg')
     <div id="loader-wrapper">
         <div id="loader">
            <ul class="cssload-flex-container">
               <li><span class="cssload-loading"></span></li>
            </ul>
         </div>
      </div>
    <div id="page" class="page">

        @include('user.partials.header')

        <main class="app-main">
            @yield('content')
        </main>

        @include('user.partials.footer')
    </div>

</div>

@include('user.partials.popup')
@include('user.partials.scripts')

</body>
</html>
