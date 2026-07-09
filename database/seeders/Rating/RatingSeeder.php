<?php

namespace Database\Seeders\Rating;

use App\Models\Feedback\Rating;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        Rating::factory(50)->create();
    }
}