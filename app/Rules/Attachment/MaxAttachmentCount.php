<?php

namespace App\Rules\Attachment;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxAttachmentCount implements ValidationRule
{
    public function __construct(
        protected int $maxCount = 5
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_array($value) && count($value) > $this->maxCount) {
            $fail("Jumlah maksimal lampiran yang diizinkan adalah {$this->maxCount} berkas.");
        }
    }
}