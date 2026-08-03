<?php

declare(strict_types=1);

namespace App\Events\Rating;

use Illuminate\Foundation\Events\Dispatchable;

class RatingRepliedEvent
{
    use Dispatchable;

    public function __construct(
        public readonly int $ratingId,
        public readonly int $replyId,
        public readonly int $employeeId,
    ) {}
}