<?php

namespace App\Mail\Student;

use App\Mail\Traits\HasMailConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportSubmittedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasMailConfiguration;

    public string $studentName;
    public string $unitName;
    public string $reportTitle;
    public string $trackingCode;
    public string $priority;
    public string $viewUrl;

    public function __construct(
        string $studentName,
        string $unitName,
        string $reportTitle,
        string $trackingCode,
        string $priority = 'medium',
        ?string $viewUrl = null
    ) {
        $this->studentName = $this->formatStudentName($studentName);
        $this->unitName = $unitName;
        $this->reportTitle = $reportTitle;
        $this->trackingCode = $trackingCode;
        $this->priority = ucfirst($priority);
        $this->viewUrl = $viewUrl ?? route('student.reports.show', $trackingCode);
    }

    public function envelope(): Envelope
    {
        $from = $this->defaultFrom();

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($from['address'], $from['name']),
            subject: 'Laporan Anda Berhasil Dikirim - ' . $this->trackingCode,
            tags: ['student', 'report', 'confirmation'],
            metadata: [
                'type' => 'report_submitted',
                'tracking_code' => $this->trackingCode,
                'priority' => $this->priority,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.student.report-submitted',
            with: [
                'studentName' => $this->studentName,
                'unitName' => $this->unitName,
                'reportTitle' => $this->reportTitle,
                'trackingCode' => $this->trackingCode,
                'priority' => $this->priority,
                'viewUrl' => $this->viewUrl,
            ],
        );
    }
}