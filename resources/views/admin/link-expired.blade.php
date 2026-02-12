<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Link Expired</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .expired-card {
            max-width: 420px;
            width: 100%;
            border: none;
            border-radius: 15px;
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            background-color: #ffe5e5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .icon-circle i {
            font-size: 40px;
            color: #dc3545;
        }
    </style>
</head>

<body>

<div class="card expired-card shadow-lg text-center p-4">
    
    <div class="icon-circle">
        <i class="bi bi-clock-history"></i>
    </div>

    <h3 class="mb-3 text-danger">Link Expired</h3>

    <p class="text-muted">
        The link you used has expired or is no longer valid.  
        Please request a new link to continue.
    </p>

    <div class="mt-4">
        <a href="{{route('admin.login')}}" class="btn btn-danger px-4">
            Click Here
        </a>
    </div>

</div>

</body>
</html>
