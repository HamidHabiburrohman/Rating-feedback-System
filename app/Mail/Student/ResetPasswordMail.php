<?php

namespace App\Mail\Student;

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
    public string $studentName;
    public int $expiresInMinutes;

    public function __construct(string $resetUrl, string $studentName = 'Student', int $expiresInMinutes = 60)
    {
        $this->resetUrl = $resetUrl;
        $this->studentName = $this->formatStudentName($studentName);
        $this->expiresInMinutes = $expiresInMinutes;
    }

    public function envelope(): Envelope
    {
        $from = $this->defaultFrom();
        $replyTo = $this->defaultReplyTo();

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($from['address'], $from['name']),
            replyTo: [new \Illuminate\Mail\Mailables\Address($replyTo['address'], $replyTo['name'])],
            subject: 'Reset Password - ITENAS Units Portal',
            tags: ['student', 'password-reset'],
            metadata: [
                'type' => 'student_password_reset',
                'recipient_name' => $this->studentName,
            ]
    );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.student.reset-password',
            with: [
                'resetUrl' => $this->resetUrl,
                'studentName' => $this->studentName,
                'expiresInMinutes' => $this->expiresInMinutes,
            ]
    );
    }
}