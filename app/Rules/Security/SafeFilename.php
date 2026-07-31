<?php

namespace App\Rules\Security;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SafeFilename implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('Nama berkas harus berupa string.');
            return;
        }

        if (str_contains($value, '..') || str_contains($value, '/') || str_contains($value, '\\') || str_contains($value, "\0")) {
            $fail('Nama berkas mengandung karakter ilegal atau potensi ancaman keamanan.');
            return;
        }

        $disallowedExtensions = ['php', 'exe', 'sh', 'bat', 'phtml', 'htaccess'];
        $extension = strtolower(pathinfo($value, PATHINFO_EXTENSION));

        if (in_array($extension, $disallowedExtensions, true)) {
            $fail('Nama berkas menggunakan ekstensi yang dilarang.');
        }
    }
}