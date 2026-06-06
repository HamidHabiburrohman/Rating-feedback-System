<?php

namespace Database\Seeders;

use App\Models\Report\Report;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        if (Report::count() === 0) {
            Report::factory(30)->create();
        }
    }
}