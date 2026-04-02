<?php

namespace Database\Seeders;

use App\Models\RatingCategory;
use Illuminate\Database\Seeder;

class RatingCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fasilitas',
                'slug' => 'facility',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Pelayanan',
                'slug' => 'service',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Kualitas',
                'slug' => 'quality',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Kebersihan',
                'slug' => 'cleanliness',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Aksesibilitas',
                'slug' => 'accessibility',
                'is_active' => false,
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            RatingCategory::create($category);
        }
    }
}