<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CommonMailNotification extends Notification
{
    protected $subject;
    protected $slot;

    public function __construct($subject, $slot)
    {
        $this->subject = $subject;
        $this->slot = $slot;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->view('emails.layout', [
                'subject' => $this->subject,
                'slot' => $this->slot,
            ]);
    }
}
