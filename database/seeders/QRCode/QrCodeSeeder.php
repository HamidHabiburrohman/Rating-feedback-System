<?php

namespace Database\Seeders\QRCode;

use App\Models\Authentication\Admin;
use App\Models\Unit\Unit;
use App\Services\Admin\QRCode\QrCodeService;
use Illuminate\Database\Seeder;

class QrCodeSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();

        if (!$admin) {
            $this->command?->error('No admin found. Please run AdminSeeder first.');
            return;
        }

        $units = Unit::where('is_active', true)
            ->whereDoesntHave('qrCodes', function ($query) {
                $query->where('is_active', true);
            })
            ->inRandomOrder()
            ->limit(20)
            ->get();

        if ($units->isEmpty()) {
            $this->command?->warn('No eligible units found for QR Code seeding.');
            return;
        }

        $qrCodeService = app(QrCodeService::class);
        $generatedCount = 0;

        $this->command?->info('Generating QR Codes for ' . $units->count() . ' units...');

        foreach ($units as $unit) {
            try {
                $qrCodeService->generate($unit->id, $admin->id);
                $generatedCount++;
                $this->command?->line("  ✔ Generated QR for Unit: {$unit->name}");
            } catch (\Exception $e) {
                $this->command?->warn("  ✘ Failed for Unit ID {$unit->id}: " . $e->getMessage());
            }
        }

        $this->command?->info("Successfully generated {$generatedCount} QR Codes.");
    }
}