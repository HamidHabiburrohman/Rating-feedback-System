<?php

declare(strict_types=1);

namespace App\Events\Rating;

use Illuminate\Foundation\Events\Dispatchable;

class RatingStatusUpdatedEvent
{
    use Dispatchable;

    public function __construct(
        public readonly int $ratingId,
        public readonly string $previousStatus,
        public readonly string $currentStatus,
    ) {}
}