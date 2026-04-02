<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class GeneralNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $subject;
    protected string $message;
    protected array $data;
    protected array $channels;

    public function __construct(string $subject, string $message, array $data = [])
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->data = $data;
        $this->channels = ['database'];
        
        if (isset($data['channels'])) {
            $this->channels = $data['channels'];
        }
    }

    public function via($notifiable): array
    {
        return $this->channels;
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->subject)
            ->greeting('Halo ' . ($notifiable->nama ?? 'User') . '!')
            ->line($this->message);

        if (isset($this->data['url'])) {
            $mail->action('Lihat Detail', $this->data['url']);
        }

        $mail->line('Terima kasih telah menggunakan sistem kami.');

        return $mail;
    }

    public function toDatabase($notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'subject' => $this->subject,
            'message' => $this->message,
            'data' => $this->data,
            'type' => $this->data['type'] ?? 'general',
        ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'subject' => $this->subject,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }

    public function viaChannels(array $channels): self
    {
        $this->channels = $channels;
        return $this;
    }
}