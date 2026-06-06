<?php

namespace Database\Seeders;

use App\Models\Feedback\UnitVisit;
use Illuminate\Database\Seeder;

class UnitVisitSeeder extends Seeder
{
    public function run(): void
    {
        UnitVisit::factory(100)->create();
    }
}