<?php

namespace Database\Seeders\QRCode;

use App\Models\Authentication\Admin;
use App\Models\Unit\QrCode;
use App\Models\Unit\Unit;
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

        $units = Unit::all();

        if ($units->isEmpty()) {
            $this->command?->warn('No units found. Skipping QR Code seeding.');
            return;
        }

        foreach ($units as $unit) {
            $hasActiveQr = QrCode::where('unit_id', $unit->id)
                ->where('is_active', true)
                ->exists();

            if ($hasActiveQr) {
                continue;
            }

            QrCode::factory()
                ->forUnit($unit)
                ->create([
                    'generated_by_admin_id' => $admin->id,
                ]);
        }
    }
}