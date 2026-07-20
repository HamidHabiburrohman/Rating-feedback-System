<?php

namespace App\Mail\Employee;

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
    public string $employeeName;
    public int $expiresInMinutes;

    public function __construct(string $resetUrl, string $employeeName = 'Employee', int $expiresInMinutes = 60)
    {
        $this->resetUrl = $resetUrl;
        $this->employeeName = $this->formatEmployeeName($employeeName);
        $this->expiresInMinutes = $expiresInMinutes;
    }

    public function envelope(): Envelope
    {
        $from = $this->defaultFrom();
        $replyTo = $this->defaultReplyTo();

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($from['address'], $from['name']),
            replyTo: [new \Illuminate\Mail\Mailables\Address($replyTo['address'], $replyTo['name'])],
            subject: 'Reset Password Employee - ITENAS Units Portal',
            tags: ['employee', 'password-reset'],
            metadata: [
                'type' => 'employee_password_reset',
                'recipient_name' => $this->employeeName,
            ]
    );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.employee.reset-password',
            with: [
                'resetUrl' => $this->resetUrl,
                'employeeName' => $this->employeeName,
                'expiresInMinutes' => $this->expiresInMinutes,
            ]
    );
    }
}