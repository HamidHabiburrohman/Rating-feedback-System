<?php

namespace App\Rules\Attachment;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class ValidAttachment implements ValidationRule
{
    public function __construct(
        protected array $allowedMimes = ['jpg', 'jpeg', 'png', 'pdf']
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value instanceof UploadedFile || !$value->isValid()) {
            $fail('Berkas yang diunggah tidak valid.');
            return;
        }

        $extension = strtolower($value->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedMimes, true)) {
            $fail('Format berkas tidak diizinkan. Format yang diperbolehkan: ' . implode(', ', $this->allowedMimes));
        }
    }
}