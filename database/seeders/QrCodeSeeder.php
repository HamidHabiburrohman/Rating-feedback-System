<?php

namespace Database\Seeders;

use App\Models\Unit\QrCode;
use App\Models\Unit\Unit;
use App\Models\Authentication\Admin;
use Illuminate\Database\Seeder;

class QrCodeSeeder extends Seeder
{
    public function run(): void
    {
        $units = Unit::all();
        $admin = Admin::first();

        foreach ($units as $unit) {
            QrCode::factory()
                ->forUnit($unit)
                ->create([
                    'generated_by_admin_id' => $admin?->id,
                ]);
        }
    }
}