<?php

namespace Database\Seeders;

use App\Models\UnitType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
                'name' => 'Cafetaria',
                'slug' => 'cafetaria',
                'icon_key' => 'coffee',
                'description' => 'Tempat makan dan berkumpul',
                'is_active' => true,
            ],
            [
                'name' => 'Sports Center',
                'slug' => 'sports-center',
                'icon_key' => 'dumbbell',
                'description' => 'Fasilitas olahraga dan kebugaran',
                'is_active' => true,
            ],
            [
                'name' => 'Student Lounge',
                'slug' => 'student-lounge',
                'icon_key' => 'sofa',
                'description' => 'Ruang santai mahasiswa',
                'is_active' => false,
            ],
        ];

        foreach ($types as $type) {
            UnitType::create($type);
        }
    }
}