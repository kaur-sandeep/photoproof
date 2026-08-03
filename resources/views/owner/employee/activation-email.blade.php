<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activation - Photo Proof</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{
            background:#0b1424;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:20px;
        }
        .activation-wrapper{
            width:100%;
            max-width:440px;
        }
        .activation-logo{
            text-align:center;
            margin-bottom:24px;
        }
        .activation-logo img{
            height:40px;
        }
        .activation-card{
            background:#111a2c;
            border:1px solid #22304a;
            border-radius:16px;
            padding:44px 36px;
            color:#fff;
            text-align:center;
        }
        .activation-icon{
            width:72px;
            height:72px;
            margin:0 auto 20px;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:50%;
        }
        .activation-icon.success{
            background:rgba(34,197,94,0.12);
            border:1px solid rgba(34,197,94,0.4);
        }
        .activation-icon.failed{
            background:rgba(239,68,68,0.12);
            border:1px solid rgba(239,68,68,0.4);
        }
        .activation-icon svg{
            width:36px;
            height:36px;
        }
        .activation-title{
            font-size:22px;
            font-weight:700;
            margin-bottom:10px;
        }
        .activation-title.success{color:#22c55e;}
        .activation-title.failed{color:#f87171;}
        .activation-message{
            color:#9aa5b8;
            font-size:14px;
            line-height:1.6;
        }
        .activation-footer-text{
            text-align:center;
            color:#5b6579;
            font-size:12px;
            margin-top:24px;
        }
    </style>
</head>
<body>

    <div class="activation-wrapper">

        <div class="activation-logo">
            <img src="{{ asset('user/images/logo-white.png') }}" alt="Photo Proof">
        </div>

        <div class="activation-card">

            @if($success)
                <div class="activation-icon success">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                </div>
                <div class="activation-title success">Account Activated</div>
            @else
                <div class="activation-icon failed">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                </div>
                <div class="activation-title failed">Activation Failed</div>
            @endif

            <p class="activation-message">{{ $message }}</p>

        </div>

        <p class="activation-footer-text">&copy; {{ date('Y') }} Photo Proof. All Rights Reserved.</p>

    </div>

</body>
</html>