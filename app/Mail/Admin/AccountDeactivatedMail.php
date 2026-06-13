<?php

namespace App\Mail\Admin;

use App\Mail\Traits\HasMailConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountDeactivatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels, HasMailConfiguration;

    public string $recipientName;
    public string $recipientEmail;
    public string $role;
    public ?string $reason;
    public string $deactivatedByName;
    public string $supportEmail;

    public function __construct(
        string $recipientName,
        string $recipientEmail,
        string $role,
        ?string $reason = null,
        string $deactivatedByName = 'Super Admin',
        ?string $supportEmail = null
    ) {
        $this->recipientName = $recipientName;
        $this->recipientEmail = $recipientEmail;
        $this->role = ucwords(str_replace('_', ' ', $role));
        $this->reason = $reason;
        $this->deactivatedByName = $deactivatedByName;
        $this->supportEmail = $supportEmail ?? 'support@itenas.ac.id';
    }

    public function envelope(): Envelope
    {
        $from = $this->defaultFrom();

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($from['address'], $from['name']),
            subject: 'Akun Anda Telah Dinonaktifkan - ITENAS Units Portal',
            tags: ['account', 'deactivated'],
            metadata: [
                'type' => 'account_deactivated',
                'role' => $this->role,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.shared.account-deactivated',
            with: [
                'recipientName' => $this->recipientName,
                'recipientEmail' => $this->recipientEmail,
                'role' => $this->role,
                'reason' => $this->reason,
                'deactivatedByName' => $this->deactivatedByName,
                'supportEmail' => $this->supportEmail,
            ],
        );
    }
}