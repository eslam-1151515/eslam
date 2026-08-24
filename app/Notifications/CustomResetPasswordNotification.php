<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('🔑 طلب إعادة تعيين كلمة السر — Order Saif')
            ->from(config('mail.from.address', 'emamrady631@gmail.com'), 'Order Saif — دعم الحسابات')
            ->view('emails.reset_password', [
                'resetUrl' => $resetUrl,
                'name' => $notifiable->name,
            ]);
    }
}
