<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.head')
</head>

<body class="login-page bg-body-tertiary">

<div class="login-box" style="width:400px;margin:auto;margin-top:10%;">

    <div class="card shadow">

        <div class="card-body">

            <h4 class="text-center mb-4">Reset password.</h4>

            
         @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

            <form id="adminLoginForm" method="POST" action="{{ route('admin.reset.password') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="input-group mb-3">
                    <input type="email" name="email" id ="email" value ="{{ $email }}" class="form-control" readonly >
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" id ="password" class="form-control" placeholder="password" require>
                </div>
                <div class="input-group mb-3">
                     <input type="password" name="confirm_password" id ="confirm_password" class="form-control" placeholder="confirm password" require>
                </div>
                <span class="text-danger small" id="emailError"></span>
                <div class="input-group mb-3">
                    <button type="submit" class="btn btn-primary btn-block">
                        Reset Password
                    </button>
                </div>
            </form>

        </div>

    </div>

</div>

@include('admin.partials.scripts')

</body>
</html>
