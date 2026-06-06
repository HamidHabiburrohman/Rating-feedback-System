<?php

namespace Database\Seeders;

use App\Models\Unit\UnitDepartment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UnitDepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'Fakultas Teknik',
            'Fakultas Ekonomi',
            'Fakultas Hukum',
            'Fakultas Kedokteran',
            'Fakultas Ilmu Komputer',
            'Fakultas Psikologi',
            'Direktorat Akademik',
            'Kemahasiswaan',
            'UPT Perpustakaan',
            'UPT Laboratorium',
        ];

        foreach ($departments as $department) {
            UnitDepartment::firstOrCreate(
                ['name' => $department],
                [
                    'slug' => Str::slug($department),
                    'is_active' => true,
                ]
            );
        }
    }
}