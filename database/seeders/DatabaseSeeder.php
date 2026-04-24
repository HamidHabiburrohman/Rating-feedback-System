<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            UnitTypeSeeder::class,
            UnitDepartmentSeeder::class,
            RatingCategorySeeder::class,
            FacilitySeeder::class,
            SettingSeeder::class,

            UnitSeeder::class,
            StudentSessionSeeder::class,
            UnitPhotoSeeder::class,
            UnitFacilitySeeder::class,
            UnitVisitSeeder::class,
            RatingSeeder::class,
            RatingScoreSeeder::class,
            AdminReplySeeder::class,
            ReportSeeder::class,
            StudentSeeder::class,
            ModerationLogSeeder::class,
        ]);
    }
}