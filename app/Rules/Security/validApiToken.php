<?php

namespace App\Rules\Security;

use Closure;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidApiToken implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || empty($value)) {
            $fail('Token API tidak boleh kosong.');
            return;
        }

        // $token = PersonalAccessToken::findToken($value);

        // if (!$token) {
        //     $fail('Token API tidak valid.');
        //     return;
        // }

        // if ($token->expires_at && $token->expires_at->isPast()) {
        //     $fail('Token API sudah kadaluarsa.');
        // }
    }
}