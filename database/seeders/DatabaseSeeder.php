<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // AdminSeeder::class,
            // StudentSeeder::class,
            // EmployeeSeeder::class,
            // UnitTypeSeeder::class,
            // UnitDepartmentSeeder::class,
            // FacilitySeeder::class,
            // UnitSeeder::class,
            // UnitFacilitySeeder::class,
            // UnitPhotoSeeder::class,
            // QrCodeSeeder::class,
            // RatingCategorySeeder::class,
            // RatingSeeder::class,
            // RatingScoreSeeder::class,
            // RatingAttachmentSeeder::class,
            // RatingReplySeeder::class,
            // UnitVisitSeeder::class,
            // ReportCategorySeeder::class,
            // ReportSeeder::class,
            // ReportAttachmentSeeder::class,
            // ReportReplySeeder::class,
            // ReportStatusHistorySeeder::class,
            // EmployeePositionSeeder::class,
            // EmployeeUnitAssignmentSeeder::class,
            NotificationSeeder::class,
            SettingSeeder::class,
            ExportSeeder::class,
            ModerationLogSeeder::class,
        ]);
    }
}