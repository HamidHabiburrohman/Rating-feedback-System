<?php

namespace Database\Seeders;

use App\Models\Units\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        Unit::factory(30)->create();
    }
}