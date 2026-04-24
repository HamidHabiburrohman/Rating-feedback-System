<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\UnitType;
use App\Models\UnitDepartment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UnitSeeder extends Seeder
{
    private $unitData = [
        [
            'name' => 'Lab Komputer Dasar',
            'type' => 'Laboratorium',
            'department' => 'Fakultas Ilmu Komputer',
            'description' => 'Laboratorium komputer untuk praktikum dasar pemrograman dan aplikasi perkantoran.',
            'building' => 'Gedung FIK',
            'floor' => 'Lantai 2',
            'capacity' => 40,
        ],
        [
            'name' => 'Perpustakaan Pusat',
            'type' => 'Perpustakaan',
            'department' => 'UPT Perpustakaan',
            'description' => 'Perpustakaan utama dengan koleksi buku, jurnal, dan akses digital lengkap.',
            'building' => 'Gedung Perpustakaan',
            'floor' => 'Lantai 1-3',
            'capacity' => 200,
        ],
        [
            'name' => 'Klinik Pratama',
            'type' => 'Klinik',
            'department' => 'UPT Kesehatan',
            'description' => 'Layanan kesehatan dasar untuk mahasiswa dan staff kampus.',
            'building' => 'Gedung Klinik',
            'floor' => 'Lantai 1',
            'capacity' => 30,
        ],
        [
            'name' => 'Ruang Kelas 101',
            'type' => 'Ruang Kelas',
            'department' => 'Fakultas Teknik',
            'description' => 'Ruang kelas standar dengan kapasitas 50 orang, dilengkapi AC dan proyektor.',
            'building' => 'Gedung Fakultas Teknik',
            'floor' => 'Lantai 1',
            'capacity' => 50,
        ],
        [
            'name' => 'Auditorium Kampus',
            'type' => 'Auditorium',
            'department' => 'Direktorat Kemahasiswaan',
            'description' => 'Auditorium utama untuk acara wisuda, seminar, dan kegiatan besar kampus.',
            'building' => 'Gedung Serbaguna',
            'floor' => 'Lantai 2',
            'capacity' => 500,
        ],
        [
            'name' => 'Kantin Pusat',
            'type' => 'Kantin',
            'department' => 'Direktorat Kemahasiswaan',
            'description' => 'Kantin utama dengan berbagai pilihan makanan dan minuman.',
            'building' => 'Student Center',
            'floor' => 'Lantai Dasar',
            'capacity' => 150,
        ],
        [
            'name' => 'GOR Kampus',
            'type' => 'Olahraga',
            'department' => 'Direktorat Kemahasiswaan',
            'description' => 'Gedung olahraga serbaguna untuk basket, futsal, dan bulutangkis.',
            'building' => 'Gedung Olahraga',
            'floor' => 'Lantai 1',
            'capacity' => 300,
        ],
        [
            'name' => 'Co-working Space',
            'type' => 'Layanan',
            'department' => 'Fakultas Ilmu Komputer',
            'description' => 'Ruang kerja bersama dengan fasilitas WiFi cepat dan area diskusi.',
            'building' => 'Gedung FIK',
            'floor' => 'Lantai 3',
            'capacity' => 60,
        ],
    ];

    public function run(): void
    {
        $unitTypes = UnitType::all()->keyBy('name');
        $departments = UnitDepartment::all()->keyBy('name');

        $usedNames = [];

        foreach ($this->unitData as $index => $data) {
            $typeName = $data['type'];
            $deptName = $data['department'];
            
            $unitType = $unitTypes[$typeName] ?? $unitTypes->first();
            $department = $departments[$deptName] ?? $departments->first();
            
            $uniqueName = $data['name'];
            $counter = 1;
            while (in_array($uniqueName, $usedNames)) {
                $uniqueName = $data['name'] . ' ' . $counter;
                $counter++;
            }
            $usedNames[] = $uniqueName;
            
            Unit::create([
                'code' => strtoupper(substr($typeName, 0, 3) . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT)),
                'name' => $uniqueName,
                'slug' => Str::slug($uniqueName),
                'unit_type_id' => $unitType->id,
                'unit_department_id' => $department->id,
                'description' => $data['description'],
                'location' => $data['building'] . ' ' . $data['floor'],
                'building' => $data['building'],
                'floor' => $data['floor'],
                'phone' => rand(0, 1) ? '021-555' . rand(1000, 9999) : null,
                'email' => rand(0, 1) ? strtolower(str_replace(' ', '', $uniqueName)) . '@campus.ac.id' : null,
                'open_time' => '08:00:00',
                'close_time' => ['16:00:00', '17:00:00', '20:00:00'][array_rand(['16:00:00', '17:00:00', '20:00:00'])],
                'capacity' => $data['capacity'],
                'is_active' => true,
                'operational_status' => ['open', 'full', 'maintenance'][array_rand(['open', 'full', 'maintenance'])],
                'avg_rating' => round(rand(30, 48) / 10, 1),
                'total_ratings' => rand(5, 50),
                'avg_facility_score' => round(rand(30, 48) / 10, 1),
                'avg_service_score' => round(rand(30, 48) / 10, 1),
                'avg_quality_score' => round(rand(30, 48) / 10, 1),
                'last_rated_at' => now()->subDays(rand(1, 60)),
                'metadata' => json_encode([
                    'has_ac' => (bool)rand(0, 1),
                    'has_wifi' => (bool)rand(0, 1),
                    'has_projector' => (bool)rand(0, 1),
                ]),
                'created_at' => now()->subMonths(rand(1, 6)),
                'updated_at' => now(),
            ]);
        }
    }
}