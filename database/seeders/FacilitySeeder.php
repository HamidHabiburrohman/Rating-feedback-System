<?php

namespace Database\Seeders;

use App\Models\Unit\Facility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            ['name' => 'AC', 'icon_key' => 'air-conditioner'],
            ['name' => 'Proyektor', 'icon_key' => 'projector'],
            ['name' => 'WiFi', 'icon_key' => 'wifi'],
            ['name' => 'Whiteboard', 'icon_key' => 'whiteboard'],
            ['name' => 'Toilet', 'icon_key' => 'toilet'],
            ['name' => 'Kantin', 'icon_key' => 'cafe'],
            ['name' => 'Parkir', 'icon_key' => 'parking'],
            ['name' => 'Musala', 'icon_key' => 'mosque'],
            ['name' => 'Loker', 'icon_key' => 'locker'],
            ['name' => 'Speaker', 'icon_key' => 'speaker'],
            ['name' => 'Kursi Roda', 'icon_key' => 'wheelchair'],
            ['name' => 'Ruang Tunggu', 'icon_key' => 'waiting-room'],
            ['name' => 'Komputer', 'icon_key' => 'computer'],
            ['name' => 'Printer', 'icon_key' => 'printer'],
            ['name' => 'Air Minum', 'icon_key' => 'water-dispenser'],
        ];

        foreach ($facilities as $facility) {
            Facility::firstOrCreate(
                ['name' => $facility['name']],
                [
                    'slug' => Str::slug($facility['name']),
                    'icon_key' => $facility['icon_key'],
                    'is_active' => true,
                ]
            );
        }
    }
}