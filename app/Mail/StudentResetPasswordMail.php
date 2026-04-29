<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $resetUrl;
    public string $studentName;

    public function __construct(string $resetUrl, string $studentName = 'Student')
    {
        $this->resetUrl = $resetUrl;
        $this->studentName = $studentName;
    }

    public function build()
    {
        return $this->subject('Reset Password Itenas Portal')
            ->markdown('emails.student.reset-password')
            ->with([
                'resetUrl' => $this->resetUrl,
                'studentName' => $this->studentName,
            ]);
    }
}
