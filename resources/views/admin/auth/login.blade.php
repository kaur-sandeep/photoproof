<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.head')
</head>
<style>
    .card-header {
        background: radial-gradient(circle at 55% 35%, #0f2a57 0%, #0b1f3f 35%, #081735 55%, #050e22 75%, #030812 100%), linear-gradient(to bottom, #0a1c3a 0%, #051022 60%, #020611 100%);
        text-align: center;
    }
</style>
<body class="login-page bg-body-tertiary">

<div class="login-box" style="width:400px;margin:auto;margin-top:10%;">

    <div class="card shadow">
         <div class="card-header">
            <!-- <h4 class="text-center mb-4">Admin Login</h4> -->
             <img src="/user/images/logo-white.png" width="200" height="" alt="header-logo">
            </div>
        <div class="card-body">
           
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form id="adminLoginForm" method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="input-group mb-3">
                    <input type="email" name="email" id ="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                </div>
                <span class="text-danger small" id="emailError"></span>
                <div class="input-group mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" value="{{ old('password') }}">
                </div>
                <span class="text-danger small" id="passwordError"></span>
                <div class="input-group mb-3">
                    <button type="submit" class="btn btn-primary btn-block">
                        Login
                    </button>
                </div>
                <p class="mb-1"><a href="{{ route('admin.forgot-password') }}">Forgot Password ?</a></p>
            </form>

        </div>

    </div>

</div>

@include('admin.partials.scripts')

</body>
</html>











