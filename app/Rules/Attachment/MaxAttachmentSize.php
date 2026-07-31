<?php

namespace App\Rules\Attachment;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class MaxAttachmentSize implements ValidationRule
{
    public function __construct(
        protected int $maxKilobytes = 5120 // Default 5MB
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value instanceof UploadedFile && $value->getSize() > ($this->maxKilobytes * 1024)) {
            $fail("Ukuran berkas tidak boleh melebihi " . ($this->maxKilobytes / 1024) . " MB.");
        }
    }
}