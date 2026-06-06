<?php

namespace Database\Seeders;

use App\Models\Employee\EmployeePosition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmployeePositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            'Kepala Laboratorium',
            'Asisten Laboratorium',
            'Teknisi',
            'Administrasi',
            'Manajer',
            'Supervisor',
            'Staff',
            'Koordinator',
        ];

        foreach ($positions as $position) {
            EmployeePosition::firstOrCreate(
                ['name' => $position],
                [
                    'slug' => Str::slug($position),
                    'is_active' => true,
                ]
            );
        }
    }
}