<?php

namespace Database\Seeders;

use App\Models\Unit\UnitType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UnitTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Laboratorium', 'icon_key' => 'flask'],
            ['name' => 'Perpustakaan', 'icon_key' => 'book-open'],
            ['name' => 'Ruang Kelas', 'icon_key' => 'presentation'],
            ['name' => 'Auditorium', 'icon_key' => 'theater'],
            ['name' => 'Kantin', 'icon_key' => 'coffee'],
            ['name' => 'Olahraga', 'icon_key' => 'dumbbell'],
            ['name' => 'Klinik', 'icon_key' => 'heart-pulse'],
            ['name' => 'Layanan', 'icon_key' => 'building'],
        ];

        foreach ($types as $type) {
            UnitType::firstOrCreate(
                ['name' => $type['name']],
                [
                    'slug' => Str::slug($type['name']),
                    'icon_key' => $type['icon_key'],
                    'is_active' => true,
                ]
            );
        }
    }
}