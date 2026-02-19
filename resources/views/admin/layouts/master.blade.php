<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.head')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

<div class="app-wrapper">

    @include('admin.partials.header')
    @include('admin.partials.sidebar')

    <main class="app-main p-3">
        @yield('content')
    </main>

    @include('admin.partials.footer')

</div>

@include('admin.partials.popup')

@include('admin.partials.scripts')

</body>
</html>
