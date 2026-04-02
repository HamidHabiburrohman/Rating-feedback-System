<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\UnitType;
use App\Models\UnitDepartment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UnitSeeder extends Seeder
{
    private $unitNames = [
        'Laboratorium' => [
            'Lab Komputer Dasar', 'Lab Jaringan Komputer', 'Lab Basis Data', 'Lab Rekayasa Perangkat Lunak',
            'Lab Multimedia', 'Lab Robotika', 'Lab Kecerdasan Buatan', 'Lab Sistem Informasi',
            'Lab Keamanan Siber', 'Lab Pemrograman Web', 'Lab Jaringan Nirkabel', 'Lab Internet of Things',
            'Lab Cloud Computing', 'Lab Game Development', 'Lab Animasi Digital', 'Lab Desain Grafis',
            'Lab Mikrokontroler', 'Lab Elektronika Dasar', 'Lab Tenaga Listrik', 'Lab Mesin CNC',
            'Lab CAD/CAM', 'Lab Mekatronika', 'Lab Otomasi Industri', 'Lab Material Teknik',
            'Lab Fisika Dasar', 'Lab Kimia Dasar', 'Lab Biologi', 'Lab Mikrobiologi',
            'Lab Biokimia', 'Lab Farmasi', 'Lab Analisis Kesehatan', 'Lab Bahasa',
        ],
        'Perpustakaan' => [
            'Perpustakaan Pusat', 'Perpustakaan Fakultas Teknik', 'Perpustakaan Fakultas Ekonomi',
            'Perpustakaan Fakultas Hukum', 'Perpustakaan Fakultas Kedokteran', 'Perpustakaan Fakultas Ilmu Komputer',
            'Perpustakaan Fakultas Psikologi', 'Perpustakaan Digital', 'Perpustakaan Referensi',
            'Ruang Baca Fakultas Teknik', 'Ruang Baca Fakultas Ekonomi', 'Ruang Baca Fakultas Hukum',
            'Ruang Baca Fakultas Kedokteran', 'Ruang Baca Fakultas Ilmu Komputer', 'Ruang Baca Fakultas Psikologi',
            'Perpustakaan Lantai 1', 'Perpustakaan Lantai 2', 'Perpustakaan Lantai 3', 'Perpustakaan Lantai 4',
        ],
        'Klinik' => [
            'Klinik Pratama', 'Klinik Gigi', 'Klinik Umum', 'Klinik Kesehatan Mahasiswa',
            'Unit Kesehatan Mahasiswa', 'Poliklinik Kampus', 'Klinik Psikologi', 'Klinik Konseling',
            'Klinik Gizi', 'Klinik Farmasi', 'Klinik Mata', 'Klinik THT', 'Klinik Kulit dan Kelamin',
        ],
        'Ruang Kelas' => [
            'Ruang Kelas 101', 'Ruang Kelas 102', 'Ruang Kelas 103', 'Ruang Kelas 104', 'Ruang Kelas 105',
            'Ruang Kelas 201', 'Ruang Kelas 202', 'Ruang Kelas 203', 'Ruang Kelas 204', 'Ruang Kelas 205',
            'Ruang Kelas 301', 'Ruang Kelas 302', 'Ruang Kelas 303', 'Ruang Kelas 304', 'Ruang Kelas 305',
            'Ruang Kuliah A', 'Ruang Kuliah B', 'Ruang Kuliah C', 'Ruang Kuliah D', 'Ruang Kuliah E',
            'Ruang Seminar 1', 'Ruang Seminar 2', 'Ruang Seminar 3', 'Ruang Sidang', 'Ruang Rapat Utama',
            'Ruang Kelas LT 1', 'Ruang Kelas LT 2', 'Ruang Kelas LT 3', 'Ruang Kelas Gedung Baru',
        ],
        'Auditorium' => [
            'Auditorium Kampus', 'Auditorium Prof. Dr. Soepomo', 'Auditorium Ir. Soekarno', 'Auditorium BJ Habibie',
            'Aula Serbaguna', 'Aula Lantai 2', 'Aula Lantai 3', 'Gedung Serbaguna', 'Convention Hall',
            'Ruang Theater', 'Auditorium Fakultas Teknik', 'Auditorium Fakultas Ekonomi',
        ],
        'Kantin' => [
            'Kantin Pusat', 'Kantin Fakultas Teknik', 'Kantin Fakultas Ekonomi', 'Kantin Fakultas Hukum',
            'Kantin Fakultas Kedokteran', 'Kantin Student Center', 'Food Court Kampus', 'Kantin Lantai Dasar',
            'Kantin Lantai 2', 'Kantin Lantai 3', 'Kantin Belakang', 'Kantin Depan', 'Kantin Timur', 'Kantin Barat',
            'Warung Kopi Kampus', 'Cafetaria Student Center', 'Ruang Makan Mahasiswa', 'Kantin Kejujuran',
        ],
        'Olahraga' => [
            'GOR Kampus', 'Lapangan Basket', 'Lapangan Futsal', 'Lapangan Voli', 'Lapangan Bulutangkis',
            'Pusat Kebugaran', 'Fitness Center', 'Studio Yoga', 'Studio Dance', 'Kolam Renang',
            'Lapangan Sepak Bola', 'Lapangan Tenis', 'Squash Court', 'Wall Climbing', 'Track Lari',
            'Gedung Olahraga A', 'Gedung Olahraga B', 'Sport Hall', 'Soccer Field', 'Basketball Court',
        ],
        'Layanan' => [
            'Bank Kampus', 'ATM Center', 'Kantor Pos', 'Toko Buku', 'Co-working Space',
            'Student Lounge', 'Ruang UKM', 'Ruang Organisasi Mahasiswa', 'Sekretariat BEM',
            'Pusat Karir', 'Bimbingan Konseling', 'Beasiswa Center', 'Alumni Center', 'International Office',
            'Pusat Bahasa', 'LPPM', 'UPT Komputer', 'Pusat Data dan Informasi', 'Helpdesk Mahasiswa',
        ],
    ];

    public function run(): void
    {
        $unitTypes = UnitType::all();
        $departments = UnitDepartment::all();

        $buildings = [
            'Gedung Rektorat', 'Gedung Fakultas Teknik', 'Gedung Fakultas Ekonomi', 'Gedung Fakultas Hukum',
            'Gedung Fakultas Kedokteran', 'Gedung FIK', 'Gedung Serbaguna', 'Student Center',
            'Gedung Perpustakaan', 'Gedung Laboratorium', 'Gedung Kuliah Bersama', 'Gedung Olahraga',
            'Gedung Pascasarjana', 'Gedung Pusat Kegiatan Mahasiswa', 'Gedung Rektorat Lama',
        ];

        $floors = ['Lantai 1', 'Lantai 2', 'Lantai 3', 'Lantai 4', 'Lantai 5', 'Lantai Dasar', 'Lantai 1-2', 'Lantai 1-3'];

        // Create 30 units with specific data
        $units = [];
        $usedNames = [];

        foreach ($unitTypes as $unitType) {
            $typeName = $unitType->name;
            if (isset($this->unitNames[$typeName])) {
                $namesToUse = array_slice($this->unitNames[$typeName], 0, 4);
                foreach ($namesToUse as $name) {
                    if (count($units) >= 30) break 2;
                    
                    // Ensure name is unique
                    $uniqueName = $name;
                    $counter = 1;
                    while (in_array($uniqueName, $usedNames)) {
                        $uniqueName = $name . ' ' . $counter;
                        $counter++;
                    }
                    $usedNames[] = $uniqueName;
                    
                    $department = $departments->random();
                    
                    $units[] = [
                        'code' => strtoupper(substr($typeName, 0, 3) . '-' . str_pad(count($units) + 1, 3, '0', STR_PAD_LEFT)),
                        'name' => $uniqueName,
                        'slug' => Str::slug($uniqueName . '-' . Str::random(4)),
                        'unit_type_id' => $unitType->id,
                        'unit_department_id' => $department->id,
                        'description' => "Fasilitas {$uniqueName} yang melayani civitas akademika dengan layanan terbaik.",
                        'location' => $buildings[array_rand($buildings)] . ' ' . $floors[array_rand($floors)],
                        'building' => $buildings[array_rand($buildings)],
                        'floor' => $floors[array_rand($floors)],
                        'phone' => rand(0, 1) ? '021-555' . rand(1000, 9999) : null,
                        'email' => rand(0, 1) ? strtolower(str_replace(' ', '', $uniqueName)) . '@campus.ac.id' : null,
                        'open_time' => ['07:00:00', '08:00:00', '09:00:00'][array_rand(['07:00:00', '08:00:00', '09:00:00'])],
                        'close_time' => ['16:00:00', '17:00:00', '18:00:00', '20:00:00', '21:00:00', '22:00:00'][array_rand(['16:00:00', '17:00:00', '18:00:00', '20:00:00', '21:00:00', '22:00:00'])],
                        'capacity' => rand(20, 500),
                        'is_active' => true,
                        'operational_status' => ['open', 'full', 'maintenance', 'closed'][array_rand(['open', 'full', 'maintenance', 'closed'])],
                        'avg_rating' => round(rand(15, 50) / 10, 1),
                        'total_ratings' => rand(5, 100),
                        'avg_facility_score' => round(rand(15, 50) / 10, 1),
                        'avg_service_score' => round(rand(15, 50) / 10, 1),
                        'avg_quality_score' => round(rand(15, 50) / 10, 1),
                        'last_rated_at' => now()->subDays(rand(1, 90)),
                        'metadata' => json_encode([
                            'has_ac' => (bool)rand(0, 1),
                            'has_wifi' => (bool)rand(0, 1),
                            'has_projector' => (bool)rand(0, 1),
                        ]),
                        'created_at' => now()->subMonths(rand(1, 12)),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insert manually defined units
        foreach ($units as $unit) {
            Unit::create($unit);
        }

        // Create remaining units using factory with proper counts
        $existingCount = Unit::count();
        $remainingCount = 100 - $existingCount;

        if ($remainingCount > 0) {
            // Create units with low ratings for "Needs Attention"
            $lowRatingCount = min(15, ceil($remainingCount * 0.2));
            // Create units with medium ratings
            $mediumRatingCount = min(30, ceil($remainingCount * 0.4));
            // Create units with high ratings
            $highRatingCount = $remainingCount - $lowRatingCount - $mediumRatingCount;

            if ($lowRatingCount > 0) {
                Unit::factory()->count($lowRatingCount)->withLowRatings()->create();
            }
            
            if ($mediumRatingCount > 0) {
                Unit::factory()->count($mediumRatingCount)->withMediumRatings()->create();
            }
            
            if ($highRatingCount > 0) {
                Unit::factory()->count($highRatingCount)->withHighRatings()->create();
            }
        }
    }
}