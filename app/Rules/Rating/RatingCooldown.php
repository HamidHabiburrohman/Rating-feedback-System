<?php

namespace App\Rules\Rating;

use Closure;
use App\Models\Feedback\Rating;
use Illuminate\Contracts\Validation\ValidationRule;

class RatingCooldown implements ValidationRule
{
    public function __construct(
        protected int $userId,
        protected int $cooldownMinutes = 5
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $lastRating = Rating::where('user_id', $this->userId)
            ->latest()
            ->first();

        if ($lastRating && $lastRating->created_at->diffInMinutes(now()) < $this->cooldownMinutes) {
            $remaining = $this->cooldownMinutes - $lastRating->created_at->diffInMinutes(now());
            $fail("Harap tunggu {$remaining} menit lagi sebelum memberikan ulasan berikutnya.");
        }
    }
}