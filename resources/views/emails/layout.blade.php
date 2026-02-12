<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject ?? 'Photo Proof' }}</title>
    <style>
        /* Add common styles here */
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 8px; }
        .header { background: #3490dc; color: #fff; padding: 10px; text-align: center; border-radius: 8px 8px 0 0; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; }
        .button { display: inline-block; padding: 10px 20px; background: #3490dc; color: #fff; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <!-- <h2>{{ $header ?? 'Photo Proof' }}</h2> -->
        </div>

        <!-- Dynamic content will go here -->
        <div class="content">
            {!! $slot !!}
        </div>

        <div class="footer">
            {{ $footer ?? '© '.date('Y').' My App. All rights reserved.' }}
        </div>
    </div>
</body>
</html>
