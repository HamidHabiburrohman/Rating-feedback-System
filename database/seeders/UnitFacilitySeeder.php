<?php

namespace Database\Seeders;

use App\Models\Units\Unit;
use App\Models\Units\Facility;
use App\Models\Units\UnitFacility;
use Illuminate\Database\Seeder;

class UnitFacilitySeeder extends Seeder
{
    public function run(): void
    {
        $units = Unit::all();
        $facilities = Facility::all();

        foreach ($units as $unit) {
            $randomFacilities = $facilities->random(rand(3, 8));

            foreach ($randomFacilities as $facility) {
                UnitFacility::create([
                    'unit_id' => $unit->id,
                    'facility_id' => $facility->id,
                ]);
            }
        }
    }
}