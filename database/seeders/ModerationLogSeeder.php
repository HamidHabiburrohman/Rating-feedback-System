<?php

namespace Database\Seeders;

use App\Models\ModerationLog;
use Illuminate\Database\Seeder;

class ModerationLogSeeder extends Seeder
{
    public function run(): void
    {
        ModerationLog::factory(50)->create();
        ModerationLog::factory(20)->forRating()->create();
        ModerationLog::factory(20)->forReport()->create();
    }
}