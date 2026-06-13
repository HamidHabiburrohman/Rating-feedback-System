<?php

namespace App\Mail\Student;

use App\Mail\Traits\HasMailConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasMailConfiguration;

    public int $studentId;
    public string $studentName;
    public string $studentEmail;
    public string $verificationUrl;
    public int $expiresInHours;

    public function __construct(
        int $studentId,
        string $studentName,
        string $studentEmail,
        string $verificationUrl,
        int $expiresInHours = 24
    ) {
        $this->studentId = $studentId;
        $this->studentName = $this->formatStudentName($studentName);
        $this->studentEmail = $studentEmail;
        $this->verificationUrl = $verificationUrl;
        $this->expiresInHours = $expiresInHours;
    }

    public function envelope(): Envelope
    {
        $from = $this->defaultFrom();
        $replyTo = $this->defaultReplyTo();

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($from['address'], $from['name']),
            replyTo: [new \Illuminate\Mail\Mailables\Address($replyTo['address'], $replyTo['name'])],
            subject: 'Verifikasi Email - ITENAS Units Portal',
            tags: ['student', 'verification'],
            metadata: [
                'type' => 'student_email_verification',
                'student_id' => $this->studentId,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.student.verification',
            with: [
                'studentName' => $this->studentName,
                'verificationUrl' => $this->verificationUrl,
                'expiresInHours' => $this->expiresInHours,
            ],
        );
    }
}