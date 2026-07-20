<?php

namespace App\Mail\Student;

use App\Mail\Traits\HasMailConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasMailConfiguration;

    public int $studentId;
    public string $studentName;
    public string $studentIdentifier;
    public string $exploreUrl;

    public function __construct(
        int $studentId,
        string $studentName,
        string $studentIdentifier,
        ?string $exploreUrl = null
    ) {
        $this->studentId = $studentId;
        $this->studentName = $this->formatStudentName($studentName);
        $this->studentIdentifier = $studentIdentifier;
        $this->exploreUrl = $exploreUrl ?? route('student.units.index');
    }

    public function envelope(): Envelope
    {
        $from = $this->defaultFrom();

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($from['address'], $from['name']),
            subject: 'Selamat Datang di ITENAS Units Portal!',
            tags: ['student', 'welcome'],
            metadata: [
                'type' => 'student_welcome',
                'student_id' => $this->studentId,
            ]
    );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.student.welcome',
            with: [
                'studentName' => $this->studentName,
                'studentIdentifier' => $this->studentIdentifier,
                'exploreUrl' => $this->exploreUrl,
            ]
    );
    }
}