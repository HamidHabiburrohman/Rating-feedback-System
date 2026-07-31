<?php

namespace App\Rules\Rating;

use App\Models\Feedback\Rating;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AlreadyRatedToday implements ValidationRule
{
    public function __construct(
        protected int $userId,
        protected int $unitId
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $hasRated = Rating::where('user_id', $this->userId)
            ->where('unit_id', $this->unitId)
            ->whereDate('created_at', now()->toDateString())
            ->exists();

        if ($hasRated) {
            $fail('Anda sudah memberikan ulasan/rating untuk unit ini hari ini.');
        }
    }
}