<?php

namespace App\Mail\Student;

use App\Mail\Traits\HasMailConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportRepliedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasMailConfiguration;

    public string $studentName;
    public string $trackingCode;
    public string $reportTitle;
    public string $repliedByName;
    public string $repliedByRole;
    public string $replyPreview;
    public string $viewUrl;

    public function __construct(
        string $studentName,
        string $trackingCode,
        string $reportTitle,
        string $repliedByName,
        string $repliedByRole,
        string $replyContent,
        ?string $viewUrl = null
    ) {
        $this->studentName = $this->formatStudentName($studentName);
        $this->trackingCode = $trackingCode;
        $this->reportTitle = $reportTitle;
        $this->repliedByName = $repliedByName;
        $this->repliedByRole = ucfirst($repliedByRole);
        $this->replyPreview = $this->truncate($replyContent, 150);
        $this->viewUrl = $viewUrl ?? route('student.reports.show', $trackingCode);
    }

    public function envelope(): Envelope
    {
        $from = $this->defaultFrom();

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($from['address'], $from['name']),
            subject: "Laporan Anda Dibalas oleh {$this->repliedByName} - {$this->trackingCode}",
            tags: ['student', 'report', 'reply'],
            metadata: [
                'type' => 'report_replied',
                'tracking_code' => $this->trackingCode,
                'replied_by_role' => $this->repliedByRole,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.student.report-replied',
            with: [
                'studentName' => $this->studentName,
                'trackingCode' => $this->trackingCode,
                'reportTitle' => $this->reportTitle,
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