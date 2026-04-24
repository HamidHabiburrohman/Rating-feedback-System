<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            ['name' => 'AC', 'icon_key' => 'air-conditioner', 'is_active' => true],
            ['name' => 'WiFi', 'icon_key' => 'wifi', 'is_active' => true],
            ['name' => 'Proyektor', 'icon_key' => 'projector', 'is_active' => true],
            ['name' => 'Whiteboard', 'icon_key' => 'whiteboard', 'is_active' => true],
            ['name' => 'Komputer', 'icon_key' => 'computer', 'is_active' => true],
            ['name' => 'Printer', 'icon_key' => 'printer', 'is_active' => true],
            ['name' => 'Musala', 'icon_key' => 'mosque', 'is_active' => true],
            ['name' => 'Toilet', 'icon_key' => 'toilet', 'is_active' => true],
            ['name' => 'Kantin', 'icon_key' => 'cafe', 'is_active' => true],
            ['name' => 'Parkir Luas', 'icon_key' => 'parking', 'is_active' => true],
            ['name' => 'Akses Kursi Roda', 'icon_key' => 'wheelchair', 'is_active' => true],
            ['name' => 'Ruang Tunggu', 'icon_key' => 'waiting-room', 'is_active' => true],
            ['name' => 'Air Minum', 'icon_key' => 'water-dispenser', 'is_active' => true],
            ['name' => 'Loker', 'icon_key' => 'locker', 'is_active' => true],
            ['name' => 'Sound System', 'icon_key' => 'speaker', 'is_active' => true],
        ];

        foreach ($facilities as $facility) {
            Facility::firstOrCreate(
                ['name' => $facility['name']],
                $facility
            );
        }
    }
}