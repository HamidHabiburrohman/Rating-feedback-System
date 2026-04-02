<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\User;
use App\Models\UnitPhoto;
use Illuminate\Database\Seeder;

class UnitPhotoSeeder extends Seeder
{
    public function run(): void
    {
        $units = Unit::all();
        $adminIds = User::whereIn('role', ['admin', 'super_admin'])->pluck('id')->toArray();

        foreach ($units as $index => $unit) {
            $photoCount = rand(1, 4);
            
            for ($i = 0; $i < $photoCount; $i++) {
                UnitPhoto::create([
                    'unit_id' => $unit->id,
                    'uploaded_by_admin_id' => $adminIds[array_rand($adminIds)],
                    'original_path' => "units/unit-{$unit->id}/photo-{$i}.jpg",
                    'thumbnail_path' => "units/unit-{$unit->id}/thumb-{$i}.jpg",
                    'medium_path' => "units/unit-{$unit->id}/medium-{$i}.jpg",
                    'large_path' => "units/unit-{$unit->id}/large-{$i}.jpg",
                    'file_name' => "unit-{$unit->id}-{$i}.jpg",
                    'mime_type' => 'image/jpeg',
                    'file_size' => rand(50000, 500000),
                    'alt_text' => "Foto {$unit->name} - {$i}",
                    'sort_order' => $i,
                    'is_primary' => $i === 0,
                ]);
            }
        }
    }
}