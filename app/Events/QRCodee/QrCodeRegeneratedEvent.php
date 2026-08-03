<?php

declare(strict_types=1);

namespace App\Events\QRCode;

use Illuminate\Foundation\Events\Dispatchable;

final readonly class QrCodeRegeneratedEvent
{
    use Dispatchable;

    public function __construct(
        public int $previousQrCodeId,
        public int $newQrCodeId,
        public int $unitId,
    ) {}
}