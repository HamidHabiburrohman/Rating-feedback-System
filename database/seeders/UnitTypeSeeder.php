<?php

namespace Database\Seeders;

use App\Models\UnitType;
use Illuminate\Database\Seeder;

class UnitTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Laboratorium',
                'slug' => 'laboratorium',
                'icon_key' => 'flask',
                'description' => 'Fasilitas laboratorium untuk praktikum dan penelitian',
                'is_active' => true,
            ],
            [
                'name' => 'Perpustakaan',
                'slug' => 'perpustakaan',
                'icon_key' => 'book-open',
                'description' => 'Pusat sumber belajar dan literatur',
                'is_active' => true,
            ],
            [
                'name' => 'Klinik',
                'slug' => 'klinik',
                'icon_key' => 'heart-pulse',
                'description' => 'Layanan kesehatan untuk mahasiswa dan staff',
                'is_active' => true,
            ],
            [
                'name' => 'Ruang Kelas',
                'slug' => 'ruang-kelas',
                'icon_key' => 'presentation',
                'description' => 'Fasilitas pembelajaran perkuliahan',
                'is_active' => true,
            ],
            [
                'name' => 'Auditorium',
                'slug' => 'auditorium',
                'icon_key' => 'theater',
                'description' => 'Ruang serbaguna untuk acara besar',
                'is_active' => true,
            ],
            [
                'name' => 'Kantin',
                'slug' => 'kantin',
                'icon_key' => 'coffee',
                'description' => 'Tempat makan dan berkumpul',
                'is_active' => true,
            ],
            [
                'name' => 'Olahraga',
                'slug' => 'olahraga',
                'icon_key' => 'dumbbell',
                'description' => 'Fasilitas olahraga dan kebugaran',
                'is_active' => true,
            ],
            [
                'name' => 'Layanan',
                'slug' => 'layanan',
                'icon_key' => 'building',
                'description' => 'Fasilitas layanan umum kampus',
                'is_active' => true,
            ],
        ];

        foreach ($types as $type) {
            UnitType::create($type);
        }
    }
}