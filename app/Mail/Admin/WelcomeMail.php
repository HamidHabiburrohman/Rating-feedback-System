<?php

namespace App\Mail\Admin;

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

    public int $adminId;
    public string $adminName;
    public string $adminEmail;
    public string $role;
    public string $temporaryPassword;
    public string $loginUrl;

    public function __construct(
        int $adminId,
        string $adminName,
        string $adminEmail,
        string $role,
        string $temporaryPassword,
        ?string $loginUrl = null
    ) {
        $this->adminId = $adminId;
        $this->adminName = $this->formatAdminName($adminName);
        $this->adminEmail = $adminEmail;
        $this->role = ucwords(str_replace('_', ' ', $role));
        $this->temporaryPassword = $temporaryPassword;
        $this->loginUrl = $loginUrl ?? route('admin.login');
    }

    public function envelope(): Envelope
    {
        $from = $this->defaultFrom();

        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address($from['address'], $from['name']),
            subject: "Selamat Datang sebagai {$this->role} - ITENAS Units Portal",
            tags: ['admin', 'welcome'],
            metadata: [
                'type' => 'admin_welcome',
                'admin_id' => $this->adminId,
                'role' => $this->role,
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin.welcome',
            with: [
                'adminName' => $this->adminName,
                'adminEmail' => $this->adminEmail,
                'role' => $this->role,
                'temporaryPassword' => $this->temporaryPassword,
                'loginUrl' => $this->loginUrl,
            ],
        );
    }
}