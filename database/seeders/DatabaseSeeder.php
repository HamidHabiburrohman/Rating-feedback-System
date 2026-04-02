<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Urutan PENTING: parent tables dulu
            UserSeeder::class,              // users
            UnitTypeSeeder::class,           // unit_types
            UnitDepartmentSeeder::class,     // unit_departments
            RatingCategorySeeder::class,     // rating_categories
            FacilitySeeder::class,            // facilities
            SettingSeeder::class,             // settings

            // Child tables (punya foreign key)
            UnitSeeder::class,                // units
            StudentSessionSeeder::class,      // student_sessions
            UnitPhotoSeeder::class,            // unit_photos
            UnitFacilitySeeder::class,         // unit_facilities
            UnitVisitSeeder::class,            // unit_visits
            RatingSeeder::class,               // ratings
            RatingScoreSeeder::class,          // rating_scores
            AdminReplySeeder::class,            // admin_replies
            ReportSeeder::class,                // reports
            ModerationLogSeeder::class,         // moderation_logs
            StudentSeeder::class,            // students
            AdminReplySeeder::class,

        ]);
    }
}
