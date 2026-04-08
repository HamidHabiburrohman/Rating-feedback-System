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
        
        $placeholderPath = public_path('assets/images/UnitPlaceholder.png');

        foreach ($units as $index => $unit) {
            $photoCount = rand(1, 4);
            
            for ($i = 0; $i < $photoCount; $i++) {
                UnitPhoto::create([
                    'unit_id' => $unit->id,
                    'uploaded_by_admin_id' => $adminIds[array_rand($adminIds)],
                    'original_path' => $placeholderPath,
                    'thumbnail_path' => $placeholderPath,
                    'medium_path' => $placeholderPath,
                    'large_path' => $placeholderPath,
                    'file_name' => "placeholder-{$unit->id}-{$i}.png",
                    'mime_type' => 'image/png',
                    'file_size' => file_exists($placeholderPath) ? filesize($placeholderPath) : 5000,
                    'alt_text' => "Foto {$unit->name}",
                    'sort_order' => $i,
                    'is_primary' => $i === 0,
                ]);
            }
        }
    }
}