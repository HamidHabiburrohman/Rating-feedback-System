<?php

namespace App\Mail\Traits;

trait HasMailConfiguration
{
    public function defaultFrom(): array
    {
        return [
            'address' => config('mail.from.address', 'noreply@itenas.ac.id'),
            'name' => config('mail.from.name', 'ITENAS Units Portal')
    ];
    }

    public function defaultReplyTo(): array
    {
        return [
            'address' => config('mail.reply_to.address', 'support@itenas.ac.id'),
            'name' => config('mail.reply_to.name', 'ITENAS Support')
    ];
    }

    public function attachments(): array
    {
        return [];
    }

    protected function formatStudentName(?string $name): string
    {
        return $name ?: 'Student';
    }

    protected function formatAdminName(?string $name): string
    {
        return $name ?: 'Admin';
    }

    protected function formatEmployeeName(?string $name): string
    {
        return $name ?: 'Employee';
    }
}