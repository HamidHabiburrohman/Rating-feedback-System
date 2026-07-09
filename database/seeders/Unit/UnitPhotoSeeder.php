<?php

namespace Database\Seeders\Unit;

use App\Models\Unit\Unit;
use App\Models\Unit\UnitPhoto;
use App\Models\Authentication\Admin;
use Illuminate\Database\Seeder;

class UnitPhotoSeeder extends Seeder
{
    public function run(): void
    {
        $units = Unit::all();
        $admin = Admin::first();

        foreach ($units as $index => $unit) {
            $photoCount = rand(1, 3);

            for ($i = 0; $i < $photoCount; $i++) {
                UnitPhoto::create([
                    'unit_id' => $unit->id,
                    'uploaded_by_admin_id' => $admin?->id,
                    'disk' => 'public',
                    'original_path' => 'photos/placeholder.jpg',
                    'thumbnail_path' => 'photos/thumb/placeholder.jpg',
                    'medium_path' => 'photos/medium/placeholder.jpg',
                    'large_path' => 'photos/large/placeholder.jpg',
                    'file_name' => "placeholder-{$unit->id}-{$i}.jpg",
                    'mime_type' => 'image/jpeg',
                    'file_size' => 50000,
                    'sort_order' => $i,
                    'is_primary' => $i === 0,
                ]);
            }
        }
    }
}