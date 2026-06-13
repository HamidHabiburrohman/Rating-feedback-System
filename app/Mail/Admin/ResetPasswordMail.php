<?php

namespace App\Mail\Admin;

use App\Mail\Traits\HasMailConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasMailConfiguration;

    public string $resetUrl;
    public string $adminName;
    public int $expiresInMinutes;

    public function __construct(string $resetUrl, string $adminName = 'Admin', int $expiresInMinutes = 60)
    {
        $this->resetUrl = $resetUrl;
        $this->adminName = $this->formatAdminName($adminName);
        $this->expiresInMinutes = $expiresInMinutes;
    }

    public function envelope(): Envelope
    {
        $from = $this->defaultFrom();
        $replyTo = $this->defaultReplyTo();

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($from['address'], $from['name']),
            replyTo: [new \Illuminate\Mail\Mailables\Address($replyTo['address'], $replyTo['name'])],
            subject: 'Reset Password Admin - ITENAS Units Portal',
            tags: ['admin', 'password-reset'],
            metadata: [
                'type' => 'admin_password_reset',
                'recipient_name' => $this->adminName,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin.reset-password',
            with: [
                'resetUrl' => $this->resetUrl,
                'adminName' => $this->adminName,
                'expiresInMinutes' => $this->expiresInMinutes,
            ],
        );
    }
}