<?php

namespace Database\Seeders;

use App\Models\UnitType;
use Illuminate\Database\Seeder;

class UnitTypeSeeder extends Seeder
{
    public function run()
    {
        $unitTypes = [
            [
                'name' => 'Kesehatan',
                'description' => 'Unit layanan kesehatan untuk mahasiswa dan staf',
                'sort_order' => 1
            ],
            [
                'name' => 'Akademik',
                'description' => 'Fakultas, jurusan, dan unit pembelajaran',
                'sort_order' => 2
            ],
            [
                'name' => 'Administrasi',
                'description' => 'Unit administrasi dan keuangan kampus',
                'sort_order' => 3
            ],
            [
                'name' => 'Fasilitas',
                'description' => 'Sarana dan prasarana pendukung akademik',
                'sort_order' => 4
            ],
            [
                'name' => 'Teknologi',
                'description' => 'Unit teknologi informasi dan komputer',
                'sort_order' => 5
            ],
            [
                'name' => 'Olahraga',
                'description' => 'Fasilitas olahraga dan kebugaran',
                'sort_order' => 6
            ],
            [
                'name' => 'Kesenian',
                'description' => 'Unit seni dan budaya kampus',
                'sort_order' => 7
            ],
            [
                'name' => 'Kemahasiswaan',
                'description' => 'Unit kegiatan mahasiswa dan organisasi',
                'sort_order' => 8
            ],
            [
                'name' => 'Penelitian',
                'description' => 'Pusat penelitian dan pengembangan',
                'sort_order' => 9
            ],
            [
                'name' => 'Layanan Umum',
                'description' => 'Unit layanan umum kampus',
                'sort_order' => 10
            ]
        ];

        foreach ($unitTypes as $type) {
            UnitType::create($type);
        }

        $this->command->info('✅ 10 unit types berhasil di-seed!');
    }
}