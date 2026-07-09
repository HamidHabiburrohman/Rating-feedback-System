<?php

namespace Database\Seeders\Unit;

use App\Models\Unit\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        Unit::factory(30)->create();
    }
}