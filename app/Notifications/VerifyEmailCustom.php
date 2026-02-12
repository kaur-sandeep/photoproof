<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailCustom extends VerifyEmail
{
    public function toMail($notifiable)
    {
        // Create signed URL
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );

        // Send email using shared layout
        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->view('emails.layout', [
                'slot' => '
                    <p>Hello '.$notifiable->name.',</p>
                    <p>Please click the button below to verify your email address:</p>
                    <p><a href="'.$verificationUrl.'" class="button">Verify Email Address</a></p>
                    <p>If you have trouble clicking the button, copy and paste the URL below into your browser:</p>
                    <p>'.$verificationUrl.'</p>
                ',
            ]);
    }
}
