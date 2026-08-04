<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.head')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

<div class="app-wrapper">
    @include('admin.partials.header')
    
    @if(auth()->check() && auth()->user()->getRoleNames()->contains('super-admin'))
     @include('admin.partials.header')
    @include('admin.partials.sidebar')
    
    @endif

    @if(auth()->check() && auth()->user()->getRoleNames()->contains('owner'))
    @include('owner.partials.header')
    @include('owner.partials.sidebar')
    
    @endif

    <main class="app-main p-3">
        @yield('content')
    </main>

    @include('admin.partials.footer')

</div>

@include('admin.partials.popup')

@include('admin.partials.scripts')

</body>
</html>
