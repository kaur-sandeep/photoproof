<!DOCTYPE html>
<html lang="en">
<head>
    @include('user.partials.head')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

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

        <main class="app-main p-3">
            @yield('content')
        </main>

        @include('user.partials.footer')
    </div>

</div>


@include('user.partials.scripts')

</body>
</html>
