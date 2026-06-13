<?php

namespace App\Mail\Student;

use App\Mail\Traits\HasMailConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RatingRepliedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasMailConfiguration;

    public string $studentName;
    public string $unitName;
    public string $trackingCode;
    public string $repliedByName;
    public string $repliedByRole;
    public string $replyPreview;
    public string $viewUrl;

    public function __construct(
        string $studentName,
        string $unitName,
        string $trackingCode,
        string $repliedByName,
        string $repliedByRole,
        string $replyContent,
        ?string $viewUrl = null
    ) {
        $this->studentName = $this->formatStudentName($studentName);
        $this->unitName = $unitName;
        $this->trackingCode = $trackingCode;
        $this->repliedByName = $repliedByName;
        $this->repliedByRole = ucfirst($repliedByRole);
        $this->replyPreview = $this->truncate($replyContent, 150);
        $this->viewUrl = $viewUrl ?? route('student.ratings.show', $trackingCode);
    }

    public function envelope(): Envelope
    {
        $from = $this->defaultFrom();

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($from['address'], $from['name']),
            subject: "Rating Anda Dibalas oleh {$this->repliedByName} - {$this->unitName}",
            tags: ['student', 'rating', 'reply'],
            metadata: [
                'type' => 'rating_replied',
                'tracking_code' => $this->trackingCode,
                'replied_by_role' => $this->repliedByRole,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.student.rating-replied',
            with: [
                'studentName' => $this->studentName,
                'unitName' => $this->unitName,
                'trackingCode' => $this->trackingCode,
                'repliedByName' => $this->repliedByName,
                'repliedByRole' => $this->repliedByRole,
                'replyPreview' => $this->replyPreview,
                'viewUrl' => $this->viewUrl,
            ],
        );
    }

    private function truncate(string $text, int $length): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        return mb_substr($text, 0, $length) . '...';
    }
}