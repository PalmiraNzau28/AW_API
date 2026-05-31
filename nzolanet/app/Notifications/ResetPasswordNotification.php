<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $url
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Recuperação de Senha — NzolaNet')
            ->greeting('Olá, ' . $notifiable->nome . '!')
            ->line('Recebemos um pedido para redefinir a senha da tua conta NzolaNet.')
            ->action('Redefinir Senha', $this->url)
            ->line('Este link expira em 60 minutos.')
            ->line('Se não solicitaste a recuperação de senha, ignora este email.')
            ->salutation('Equipa NzolaNet');
    }
}
