<!DOCTYPE html>
<html lang="en">
<head>
    @include('user.partials.head')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

<div class="app-wrapper">

    @include('user.partials.header')

    <main class="app-main p-3">
        @yield('content')
    </main>

    @include('user.partials.footer')

</div>


@include('user.partials.scripts')

</body>
</html>
