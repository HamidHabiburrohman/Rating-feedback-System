<?php

namespace App\Mail\Student;

use App\Mail\Traits\HasMailConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportStatusChangedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasMailConfiguration;

    public string $studentName;
    public string $trackingCode;
    public string $reportTitle;
    public string $oldStatus;
    public string $newStatus;
    public ?string $reason;
    public ?string $changedByName;
    public string $viewUrl;

    public function __construct(
        string $studentName,
        string $trackingCode,
        string $reportTitle,
        string $oldStatus,
        string $newStatus,
        ?string $reason = null,
        ?string $changedByName = null,
        ?string $viewUrl = null
    ) {
        $this->studentName = $this->formatStudentName($studentName);
        $this->trackingCode = $trackingCode;
        $this->reportTitle = $reportTitle;
        $this->oldStatus = $this->formatStatus($oldStatus);
        $this->newStatus = $this->formatStatus($newStatus);
        $this->reason = $reason;
        $this->changedByName = $changedByName;
        $this->viewUrl = $viewUrl ?? route('student.reports.show', $trackingCode);
    }

    public function envelope(): Envelope
    {
        $from = $this->defaultFrom();

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($from['address'], $from['name']),
            subject: "Status Laporan Diubah Menjadi {$this->newStatus} - {$this->trackingCode}",
            tags: ['student', 'report', 'status-change'],
            metadata: [
                'type' => 'report_status_changed',
                'tracking_code' => $this->trackingCode,
                'new_status' => $this->newStatus,
            ]
    );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.student.report-status-changed',
            with: [
                'studentName' => $this->studentName,
                'trackingCode' => $this->trackingCode,
                'reportTitle' => $this->reportTitle,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'reason' => $this->reason,
                'changedByName' => $this->changedByName,
                'viewUrl' => $this->viewUrl,
            ]
    );
    }

    private function formatStatus(string $status): string
    {
        return ucwords(str_replace('_', ' ', $status));
    }
}