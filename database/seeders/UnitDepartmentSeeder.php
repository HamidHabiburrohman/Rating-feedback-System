<?php

namespace Database\Seeders;

use App\Models\UnitDepartment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UnitDepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Fakultas Teknik',
                'slug' => 'fakultas-teknik',
                'code' => 'FT',
                'description' => 'Fakultas Teknik',
                'is_active' => true,
            ],
            [
                'name' => 'Fakultas Ekonomi',
                'slug' => 'fakultas-ekonomi',
                'code' => 'FE',
                'description' => 'Fakultas Ekonomi dan Bisnis',
                'is_active' => true,
            ],
            [
                'name' => 'Fakultas Hukum',
                'slug' => 'fakultas-hukum',
                'code' => 'FH',
                'description' => 'Fakultas Hukum',
                'is_active' => true,
            ],
            [
                'name' => 'Fakultas Kedokteran',
                'slug' => 'fakultas-kedokteran',
                'code' => 'FK',
                'description' => 'Fakultas Kedokteran',
                'is_active' => true,
            ],
            [
                'name' => 'Fakultas Ilmu Komputer',
                'slug' => 'fakultas-ilmu-komputer',
                'code' => 'FIK',
                'description' => 'Fakultas Ilmu Komputer',
                'is_active' => true,
            ],
            [
                'name' => 'Direktorat Kemahasiswaan',
                'slug' => 'direktorat-kemahasiswaan',
                'code' => 'DITMAWA',
                'description' => 'Direktorat Kemahasiswaan',
                'is_active' => true,
            ],
            [
                'name' => 'UPT Perpustakaan',
                'slug' => 'upt-perpustakaan',
                'code' => 'UPT-LIB',
                'description' => 'Unit Pelaksana Teknis Perpustakaan',
                'is_active' => true,
            ],
            [
                'name' => 'UPT Kesehatan',
                'slug' => 'upt-kesehatan',
                'code' => 'UPT-HEALTH',
                'description' => 'Unit Pelaksana Teknis Kesehatan',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $dept) {
            UnitDepartment::create($dept);
        }
    }
}