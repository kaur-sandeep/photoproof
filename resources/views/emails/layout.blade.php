<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject ?? 'Photo Proof' }}</title>
    <style>
        /* Add common styles here */
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; padding: 20px; border-radius: 8px; }
        .header { background: #0f0f0f; color: #fff; padding: 10px; text-align: center; border-radius: 8px 8px 0 0; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; }
        .button { display: inline-block; padding: 10px 20px; background: #0f0f0f; color: #fff; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>

<table cellpadding="0" cellspacing="0" width="700" align="center">
    <tr>
        <td style="background: #0f0f0f; padding:15px 0px;  text-align:center"> <img src="{{ url('user/images/logo-white.png') }}" width="150"></td>
    </tr>
    <tr>
        <td style="background:#fff; padding:15px;">

   {!! $slot !!}


<p>Thank you for using Photo Proof.<br><br>
    Best regards,<br>
    Team Photo Proof 
</p>

        </td>


    </tr>

    <tr>
        <td style="font-size: 12px; color: #777; text-align: center; padding:10px 0px;">  {{ $footer ?? '© '.date('Y').' Photo Proof . All rights reserved.' }}
        </td>


    </tr>

</table>


    <!-- <div class="container">
        <div class="header">
            <img src="{{ url('user/images/logo-white.png') }}" width="200">
           
        </div>
        <div class="content">
            {!! $slot !!}
        </div>

        <div class="footer">
            {{ $footer ?? '© '.date('Y').' Photo Proof . All rights reserved.' }}
        </div>
    </div> -->
</body>
</html>
