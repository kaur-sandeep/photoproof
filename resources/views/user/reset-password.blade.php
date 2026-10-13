<!DOCTYPE html>
<html lang="en">
<head>
    @include('user.partials.head')

    <style>
    .card-header {
        background: radial-gradient(circle at 55% 35%, #0f2a57 0%, #0b1f3f 35%, #081735 55%, #050e22 75%, #030812 100%), linear-gradient(to bottom, #0a1c3a 0%, #051022 60%, #020611 100%);
        text-align: center;
    }
</style>
</head>

<body class="login-page bg-body-tertiary">

<div class="login-box" style="width:400px;margin:auto;margin-top:10%;">

    <div class="card shadow">
        <div class="card-header">
                    <!-- <h4 class="text-center mb-4">Admin Login</h4> -->
                    <img src="/user/images/logo-white.png" width="200" height="" alt="header-logo">
                    </div>
        <div class="card-body">

            <h4 class="text-center mb-4">Generate Password</h4>

            @if(session('success'))
                <div style="color: green;">
                    {{ session('success') }}
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
                        
      @if(!session('success'))
            <form id="resetForm" method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-3">
                    <label style="color:#000;">New Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <small id="passwordError" class="text-danger"></small>
                </div>

                <div class="mb-3">
                    <label style="color:#000;">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="confirmPassword" class="form-control">
                    <small id="confirmPasswordError" class="text-danger"></small>
                </div>

                <button type="submit" class="btn btn-primary w-100">Generate Password</button>
            </form>
            @endif
        </div>

    </div>

</div>

@include('user.partials.scripts')

</body>
</html>
