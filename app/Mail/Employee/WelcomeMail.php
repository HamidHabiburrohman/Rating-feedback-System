<?php

namespace App\Mail\Employee;

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

    public int $employeeId;
    public string $employeeName;
    public string $employeeEmail;
    public string $temporaryPassword;
    public array $assignedUnits;
    public string $loginUrl;

    public function __construct(
        int $employeeId,
        string $employeeName,
        string $employeeEmail,
        string $temporaryPassword,
        array $assignedUnits = [],
        ?string $loginUrl = null
    ) {
        $this->employeeId = $employeeId;
        $this->employeeName = $this->formatEmployeeName($employeeName);
        $this->employeeEmail = $employeeEmail;
        $this->temporaryPassword = $temporaryPassword;
        $this->assignedUnits = $assignedUnits;
        $this->loginUrl = $loginUrl ?? route('employee.login');
    }

    public function envelope(): Envelope
    {
        $from = $this->defaultFrom();

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($from['address'], $from['name']),
            subject: 'Selamat Datang sebagai Employee - ITENAS Units Portal',
            tags: ['employee', 'welcome'],
            metadata: [
                'type' => 'employee_welcome',
                'employee_id' => $this->employeeId,
            ]
    );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.employee.welcome',
            with: [
                'employeeName' => $this->employeeName,
                'employeeEmail' => $this->employeeEmail,
                'temporaryPassword' => $this->temporaryPassword,
                'assignedUnits' => $this->assignedUnits,
                'loginUrl' => $this->loginUrl,
            ]
    );
    }
}