<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $resetUrl,
        public string $adminName = 'Admin'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password Admin - Unit Rating Feedback',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin.reset-password',
            with: [
                'resetUrl' => $this->resetUrl,
                'adminName' => $this->adminName,
            ],
        );
    }
}