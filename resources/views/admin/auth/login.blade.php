<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.partials.head')
</head>

<body class="login-page bg-body-tertiary">

<div class="login-box" style="width:400px;margin:auto;margin-top:10%;">

    <div class="card shadow">

        <div class="card-body">

            <h4 class="text-center mb-4">Admin Login</h4>

            
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
                    <input type="email" name="email" id ="email" class="form-control" placeholder="Email" >
                </div>
                <span class="text-danger small" id="emailError"></span>
                <div class="input-group mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" >
                </div>
                <span class="text-danger small" id="passwordError"></span>
                <div class="input-group mb-3">
                    <button type="submit" class="btn btn-primary btn-block">
                        Login
                    </button>
                </div>
            </form>

        </div>

    </div>

</div>

@include('admin.partials.scripts')

</body>
</html>











