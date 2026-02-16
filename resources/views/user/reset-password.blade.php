<!DOCTYPE html>
<html lang="en">
<head>
    @include('user.partials.head')
</head>

<body class="login-page bg-body-tertiary">

<div class="login-box" style="width:400px;margin:auto;margin-top:10%;">

    <div class="card shadow">

        <div class="card-body">

            <h4 class="text-center mb-4">Reset password.</h4>

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
                    <label>New Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <small id="passwordError" class="text-danger"></small>
                </div>

                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" id="confirmPassword" class="form-control">
                    <small id="confirmPasswordError" class="text-danger"></small>
                </div>

                <button type="submit" class="btn btn-primary w-100">Reset Password</button>
            </form>
            @endif
        </div>

    </div>

</div>

@include('user.partials.scripts')

</body>
</html>
