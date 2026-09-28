<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Sengaja TIDAK ShouldQueue: dikirim sinkron di dalam job OTP
// agar tidak antre 2 lapis.
class ResetPasswordOtpNotification extends Notification
{
    public function __construct(
        private readonly string $code,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode Reset Password — HOLIC Barbershop')
            ->greeting('Halo!')
            ->line('Kode reset password Anda:')
            ->line('## ' . $this->code)
            ->line('Masukkan kode ini di website. Berlaku 10 menit.')
            ->line('Jika Anda tidak meminta reset password, abaikan email ini.')
            ->salutation('Salam, Tim HOLIC Barbershop');
    }
}
