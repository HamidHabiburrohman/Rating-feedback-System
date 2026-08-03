<?php

declare(strict_types=1);

namespace App\Events\QRCode;

use Illuminate\Foundation\Events\Dispatchable;

final readonly class QrCodeActivatedEvent
{
    use Dispatchable;

    public function __construct(
        public int $qrCodeId,
    ) {}
}