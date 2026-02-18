<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Admin Panel')</title>
<script>
 window.APP_URL = "{{ url('/') }}";
 </script>
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

<!-- AdminLTE v4 -->
<link rel="stylesheet" href="{{ asset('admin/css/adminlte.css') }}">
<link rel="stylesheet" href="{{ asset('admin/css/custom.css') }}">