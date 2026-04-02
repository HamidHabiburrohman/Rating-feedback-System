<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\Facility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitFacilitySeeder extends Seeder
{
    public function run(): void
    {
        $units = Unit::all();
        $facilityIds = Facility::pluck('id')->toArray();

        foreach ($units as $unit) {
            // Each unit gets 3-7 random facilities
            $selectedFacilities = array_rand(array_flip($facilityIds), rand(3, 7));
            
            foreach ($selectedFacilities as $facilityId) {
                DB::table('unit_facilities')->insert([
                    'unit_id' => $unit->id,
                    'facility_id' => $facilityId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}