<?php

namespace Database\Seeders;

use App\Models\Report\ReportCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReportCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Komentar Tidak Pantas',
            'Informasi Palsu',
            'Ujaran Kebencian',
            'Spam',
            'Konten Ilegal',
            'Pelanggaran Hak Cipta',
            'Pencemaran Nama Baik',
        ];

        foreach ($categories as $category) {
            ReportCategory::firstOrCreate(
                ['name' => $category],
                [
                    'slug' => Str::slug($category),
                    'is_active' => true,
                ]
            );
        }
    }
}