<?php

namespace App\Rules\Rating;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidRatingScore implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_numeric($value) || (int) $value < 1 || (int) $value > 5) {
            $fail('Skor rating harus berupa nilai bulat antara 1 sampai 5.');
        }
    }
}