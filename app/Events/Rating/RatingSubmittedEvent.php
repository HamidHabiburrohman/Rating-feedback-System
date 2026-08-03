<?php

declare(strict_types=1);

namespace App\Events\Rating;

use Illuminate\Foundation\Events\Dispatchable;

class RatingSubmittedEvent
{
    use Dispatchable;

    public function __construct(
        public readonly int $ratingId,
        public readonly int $unitId,
        public readonly int $studentId,
    ) {}
}