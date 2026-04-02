<?php

namespace Database\Factories;

use App\Models\Unit;
use App\Models\UnitType;
use App\Models\UnitDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    private $unitNames = [
        'Laboratorium' => [
            'Lab Komputer Dasar',
            'Lab Jaringan Komputer',
            'Lab Basis Data',
            'Lab Rekayasa Perangkat Lunak',
            'Lab Multimedia',
            'Lab Robotika',
            'Lab Kecerdasan Buatan',
            'Lab Sistem Informasi',
            'Lab Keamanan Siber',
            'Lab Pemrograman Web',
            'Lab Jaringan Nirkabel',
            'Lab Internet of Things',
            'Lab Cloud Computing',
            'Lab Game Development',
            'Lab Animasi Digital',
            'Lab Desain Grafis',
            'Lab Mikrokontroler',
            'Lab Elektronika Dasar',
            'Lab Tenaga Listrik',
            'Lab Mesin CNC',
            'Lab CAD/CAM',
            'Lab Mekatronika',
            'Lab Otomasi Industri',
            'Lab Material Teknik',
            'Lab Fisika Dasar',
            'Lab Kimia Dasar',
            'Lab Biologi',
            'Lab Mikrobiologi',
            'Lab Biokimia',
            'Lab Farmasi',
            'Lab Analisis Kesehatan',
            'Lab Bahasa',
        ],
        'Perpustakaan' => [
            'Perpustakaan Pusat',
            'Perpustakaan Fakultas Teknik',
            'Perpustakaan Fakultas Ekonomi',
            'Perpustakaan Fakultas Hukum',
            'Perpustakaan Fakultas Kedokteran',
            'Perpustakaan Fakultas Ilmu Komputer',
            'Perpustakaan Fakultas Psikologi',
            'Perpustakaan Digital',
            'Perpustakaan Referensi',
            'Ruang Baca Fakultas Teknik',
            'Ruang Baca Fakultas Ekonomi',
            'Ruang Baca Fakultas Hukum',
            'Ruang Baca Fakultas Kedokteran',
            'Ruang Baca Fakultas Ilmu Komputer',
            'Ruang Baca Fakultas Psikologi',
            'Perpustakaan Lantai 1',
            'Perpustakaan Lantai 2',
            'Perpustakaan Lantai 3',
            'Perpustakaan Lantai 4',
        ],
        'Klinik' => [
            'Klinik Pratama',
            'Klinik Gigi',
            'Klinik Umum',
            'Klinik Kesehatan Mahasiswa',
            'Unit Kesehatan Mahasiswa',
            'Poliklinik Kampus',
            'Klinik Psikologi',
            'Klinik Konseling',
            'Klinik Gizi',
            'Klinik Farmasi',
            'Klinik Mata',
            'Klinik THT',
            'Klinik Kulit dan Kelamin',
        ],
        'Ruang Kelas' => [
            'Ruang Kelas 101',
            'Ruang Kelas 102',
            'Ruang Kelas 103',
            'Ruang Kelas 104',
            'Ruang Kelas 105',
            'Ruang Kelas 201',
            'Ruang Kelas 202',
            'Ruang Kelas 203',
            'Ruang Kelas 204',
            'Ruang Kelas 205',
            'Ruang Kelas 301',
            'Ruang Kelas 302',
            'Ruang Kelas 303',
            'Ruang Kelas 304',
            'Ruang Kelas 305',
            'Ruang Kuliah A',
            'Ruang Kuliah B',
            'Ruang Kuliah C',
            'Ruang Kuliah D',
            'Ruang Kuliah E',
            'Ruang Seminar 1',
            'Ruang Seminar 2',
            'Ruang Seminar 3',
            'Ruang Sidang',
            'Ruang Rapat Utama',
            'Ruang Kelas LT 1',
            'Ruang Kelas LT 2',
            'Ruang Kelas LT 3',
            'Ruang Kelas Gedung Baru',
        ],
        'Auditorium' => [
            'Auditorium Kampus',
            'Auditorium Prof. Dr. Soepomo',
            'Auditorium Ir. Soekarno',
            'Auditorium BJ Habibie',
            'Aula Serbaguna',
            'Aula Lantai 2',
            'Aula Lantai 3',
            'Gedung Serbaguna',
            'Convention Hall',
            'Ruang Theater',
            'Auditorium Fakultas Teknik',
            'Auditorium Fakultas Ekonomi',
        ],
        'Kantin' => [
            'Kantin Pusat',
            'Kantin Fakultas Teknik',
            'Kantin Fakultas Ekonomi',
            'Kantin Fakultas Hukum',
            'Kantin Fakultas Kedokteran',
            'Kantin Student Center',
            'Food Court Kampus',
            'Kantin Lantai Dasar',
            'Kantin Lantai 2',
            'Kantin Lantai 3',
            'Kantin Belakang',
            'Kantin Depan',
            'Kantin Timur',
            'Kantin Barat',
            'Warung Kopi Kampus',
            'Cafetaria Student Center',
            'Ruang Makan Mahasiswa',
            'Kantin Kejujuran',
        ],
        'Olahraga' => [
            'GOR Kampus',
            'Lapangan Basket',
            'Lapangan Futsal',
            'Lapangan Voli',
            'Lapangan Bulutangkis',
            'Pusat Kebugaran',
            'Fitness Center',
            'Studio Yoga',
            'Studio Dance',
            'Kolam Renang',
            'Lapangan Sepak Bola',
            'Lapangan Tenis',
            'Squash Court',
            'Wall Climbing',
            'Track Lari',
            'Gedung Olahraga A',
            'Gedung Olahraga B',
            'Sport Hall',
            'Soccer Field',
            'Basketball Court',
        ],
        'Layanan' => [
            'Bank Kampus',
            'ATM Center',
            'Kantor Pos',
            'Toko Buku',
            'Co-working Space',
            'Student Lounge',
            'Ruang UKM',
            'Ruang Organisasi Mahasiswa',
            'Sekretariat BEM',
            'Pusat Karir',
            'Bimbingan Konseling',
            'Beasiswa Center',
            'Alumni Center',
            'International Office',
            'Pusat Bahasa',
            'LPPM',
            'UPT Komputer',
            'Pusat Data dan Informasi',
            'Helpdesk Mahasiswa',
        ],
    ];

    public function definition(): array
    {
        $unitType = UnitType::inRandomOrder()->first() ?? UnitType::factory()->create();
        $typeName = $unitType->name;

        // Generate nama unik dengan kombinasi agar tidak duplicate
        $prefixes = ['Utama', 'Sentra', 'Pusat', 'Unit', 'Fasilitas', 'Gedung', 'Area', 'Ruang'];
        $suffixes = ['Kampus', 'Universitas', 'Mahasiswa', 'Akademik', 'Terpadu', 'Modern', 'Digital'];

        if (isset($this->unitNames[$typeName]) && !empty($this->unitNames[$typeName])) {
            // Ambil random dari array yang ada
            $baseName = $this->faker->randomElement($this->unitNames[$typeName]);
            // Tambahkan prefix/suffix untuk variasi
            $name = $baseName . ' ' . $this->faker->randomElement($prefixes);
        } else {
            // Generate nama custom
            $name = $typeName . ' ' . $this->faker->randomElement($prefixes) . ' ' . $this->faker->numberBetween(1, 99);
        }

        $department = UnitDepartment::inRandomOrder()->first() ?? UnitDepartment::factory()->create();

        $buildings = [
            'Gedung Rektorat',
            'Gedung Fakultas Teknik',
            'Gedung Fakultas Ekonomi',
            'Gedung Fakultas Hukum',
            'Gedung Fakultas Kedokteran',
            'Gedung FIK',
            'Gedung Serbaguna',
            'Student Center',
            'Gedung Perpustakaan',
            'Gedung Laboratorium',
            'Gedung Kuliah Bersama',
            'Gedung Olahraga'
        ];

        $floors = ['Lantai 1', 'Lantai 2', 'Lantai 3', 'Lantai 4', 'Lantai 5', 'Lantai Dasar', 'Lantai 1-2', 'Lantai 1-3'];

        $openTime = $this->faker->randomElement(['07:00:00', '08:00:00', '09:00:00']);
        $closeTime = $this->faker->randomElement(['16:00:00', '17:00:00', '18:00:00', '20:00:00', '21:00:00', '22:00:00']);

        $hasRatings = $this->faker->boolean(70);
        $totalRatings = $hasRatings ? $this->faker->numberBetween(1, 150) : 0;

        if ($hasRatings) {
            $avgRating = $this->faker->randomFloat(2, 1.5, 5.0);
            $avgFacility = $this->faker->randomFloat(2, 1.5, 5.0);
            $avgService = $this->faker->randomFloat(2, 1.5, 5.0);
            $avgQuality = $this->faker->randomFloat(2, 1.5, 5.0);
            $lastRatedAt = $this->faker->dateTimeBetween('-3 months', 'now');
        } else {
            $avgRating = 0;
            $avgFacility = 0;
            $avgService = 0;
            $avgQuality = 0;
            $lastRatedAt = null;
        }

        return [
            'code' => strtoupper($this->faker->unique()->bothify('??-###')),
            'name' => $name,
            'slug' => Str::slug($name . '-' . Str::random(4)), // Tambah random string untuk unique slug
            'unit_type_id' => $unitType->id,
            'unit_department_id' => $department->id,
            'description' => $this->faker->paragraphs(3, true),
            'location' => $this->faker->randomElement($buildings) . ' ' . $this->faker->randomElement($floors),
            'building' => $this->faker->randomElement($buildings),
            'floor' => $this->faker->randomElement($floors),
            'phone' => $this->faker->boolean(60) ? $this->faker->phoneNumber() : null,
            'email' => $this->faker->boolean(50) ? strtolower(str_replace(' ', '.', $name)) . '@campus.ac.id' : null,
            'open_time' => $openTime,
            'close_time' => $closeTime,
            'capacity' => $this->faker->numberBetween(20, 1000),
            'is_active' => $this->faker->boolean(90),
            'operational_status' => $this->faker->randomElement(['open', 'full', 'maintenance', 'closed']),
            'avg_rating' => $avgRating,
            'total_ratings' => $totalRatings,
            'avg_facility_score' => $avgFacility,
            'avg_service_score' => $avgService,
            'avg_quality_score' => $avgQuality,
            'last_rated_at' => $lastRatedAt,
            'metadata' => json_encode([
                'has_ac' => $this->faker->boolean(80),
                'has_wifi' => $this->faker->boolean(70),
                'has_projector' => $this->faker->boolean(50),
                'has_water' => $this->faker->boolean(60),
                'has_toilet' => $this->faker->boolean(90),
                'has_parking' => $this->faker->boolean(85),
                'has_canteen' => $this->faker->boolean(40),
            ]),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function withLowRatings(): static
    {
        return $this->state(fn(array $attributes) => [
            'avg_rating' => $this->faker->randomFloat(2, 1.0, 2.4),
            'avg_facility_score' => $this->faker->randomFloat(2, 1.0, 2.4),
            'avg_service_score' => $this->faker->randomFloat(2, 1.0, 2.4),
            'avg_quality_score' => $this->faker->randomFloat(2, 1.0, 2.4),
            'total_ratings' => $this->faker->numberBetween(5, 30),
            'last_rated_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    public function withMediumRatings(): static
    {
        return $this->state(fn(array $attributes) => [
            'avg_rating' => $this->faker->randomFloat(2, 2.5, 3.9),
            'avg_facility_score' => $this->faker->randomFloat(2, 2.5, 3.9),
            'avg_service_score' => $this->faker->randomFloat(2, 2.5, 3.9),
            'avg_quality_score' => $this->faker->randomFloat(2, 2.5, 3.9),
            'total_ratings' => $this->faker->numberBetween(10, 80),
            'last_rated_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    public function withHighRatings(): static
    {
        return $this->state(fn(array $attributes) => [
            'avg_rating' => $this->faker->randomFloat(2, 4.0, 5.0),
            'avg_facility_score' => $this->faker->randomFloat(2, 4.0, 5.0),
            'avg_service_score' => $this->faker->randomFloat(2, 4.0, 5.0),
            'avg_quality_score' => $this->faker->randomFloat(2, 4.0, 5.0),
            'total_ratings' => $this->faker->numberBetween(20, 150),
            'last_rated_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    public function withRatings(): static
    {
        return $this->afterCreating(function (Unit $unit) {
            $ratingCount = $this->faker->numberBetween(5, 50);
            $totalScore = 0;
            $facilityScores = [];
            $serviceScores = [];
            $qualityScores = [];

            for ($i = 0; $i < $ratingCount; $i++) {
                $facilityScore = $this->faker->randomFloat(1, 2, 5);
                $serviceScore = $this->faker->randomFloat(1, 2, 5);
                $qualityScore = $this->faker->randomFloat(1, 2, 5);

                $facilityScores[] = $facilityScore;
                $serviceScores[] = $serviceScore;
                $qualityScores[] = $qualityScore;

                $overallScore = round(($facilityScore + $serviceScore + $qualityScore) / 3, 2);
                $totalScore += $overallScore;
            }

            $unit->update([
                'avg_rating' => round($totalScore / $ratingCount, 2),
                'total_ratings' => $ratingCount,
                'avg_facility_score' => round(array_sum($facilityScores) / $ratingCount, 2),
                'avg_service_score' => round(array_sum($serviceScores) / $ratingCount, 2),
                'avg_quality_score' => round(array_sum($qualityScores) / $ratingCount, 2),
                'last_rated_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            ]);
        });
    }
}
