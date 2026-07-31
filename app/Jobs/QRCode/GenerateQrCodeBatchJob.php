<?php

namespace App\Jobs\QRCode;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateQrCodeBatchJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        // TODO: Deklarasikan data/payload job
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // TODO: Logika pemrosesan background job
    }
}