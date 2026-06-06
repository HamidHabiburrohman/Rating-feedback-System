<?php

namespace Database\Seeders;

use App\Models\Feedback\RatingCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RatingCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kebersihan', 'sort_order' => 1],
            ['name' => 'Kenyamanan', 'sort_order' => 2],
            ['name' => 'Pelayanan', 'sort_order' => 3],
            ['name' => 'Fasilitas', 'sort_order' => 4],
            ['name' => 'Aksesibilitas', 'sort_order' => 5],
        ];

        foreach ($categories as $category) {
            RatingCategory::firstOrCreate(
                ['name' => $category['name']],
                [
                    'slug' => Str::slug($category['name']),
                    'is_active' => true,
                    'sort_order' => $category['sort_order'],
                    'min_score' => 1.0,
                    'max_score' => 5.0,
                    'default_score' => 3.0,
                ]
            );
        }
    }
}