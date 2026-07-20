<?php

namespace App\Mail\Student;

use App\Mail\Traits\HasMailConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RatingSubmittedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasMailConfiguration;

    public string $studentName;
    public string $unitName;
    public float $overallScore;
    public string $trackingCode;
    public string $viewUrl;

    public function __construct(
        string $studentName,
        string $unitName,
        float $overallScore,
        string $trackingCode,
        ?string $viewUrl = null
    ) {
        $this->studentName = $this->formatStudentName($studentName);
        $this->unitName = $unitName;
        $this->overallScore = $overallScore;
        $this->trackingCode = $trackingCode;
        $this->viewUrl = $viewUrl ?? route('student.ratings.show', $trackingCode);
    }

    public function envelope(): Envelope
    {
        $from = $this->defaultFrom();

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($from['address'], $from['name']),
            subject: 'Rating Anda Berhasil Dikirim - ' . $this->unitName,
            tags: ['student', 'rating', 'confirmation'],
            metadata: [
                'type' => 'rating_submitted',
                'tracking_code' => $this->trackingCode,
            ]
    );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.student.rating-submitted',
            with: [
                'studentName' => $this->studentName,
                'unitName' => $this->unitName,
                'overallScore' => $this->overallScore,
                'trackingCode' => $this->trackingCode,
                'viewUrl' => $this->viewUrl,
            ]
    );
    }
}