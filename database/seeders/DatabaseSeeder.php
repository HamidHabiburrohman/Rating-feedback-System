<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // ---------------------------------------------------------
            // SYSTEM & AUTHENTICATION
            // ---------------------------------------------------------
            \Database\Seeders\Setting\SettingSeeder::class,
            \Database\Seeders\Admin\AdminSeeder::class,
            \Database\Seeders\Student\StudentSeeder::class,
            \Database\Seeders\Employee\EmployeeSeeder::class,
            \Database\Seeders\Employee\EmployeePositionSeeder::class,

            // ---------------------------------------------------------
            // UNITS & FACILITIES
            // ---------------------------------------------------------
            \Database\Seeders\Unit\UnitTypeSeeder::class,
            \Database\Seeders\Unit\UnitDepartmentSeeder::class,
            \Database\Seeders\Facility\FacilitySeeder::class,
            \Database\Seeders\Unit\UnitSeeder::class,
            \Database\Seeders\Unit\UnitFacilitySeeder::class,
            \Database\Seeders\Unit\UnitPhotoSeeder::class,
            \Database\Seeders\QRCode\QrCodeSeeder::class,
            \Database\Seeders\Employee\EmployeeUnitAssignmentSeeder::class,

            // ---------------------------------------------------------
            // FEEDBACK & RATINGS
            // ---------------------------------------------------------
            \Database\Seeders\Rating\RatingCategorySeeder::class,
            \Database\Seeders\Rating\RatingSeeder::class,
            \Database\Seeders\Rating\RatingScoreSeeder::class,
            \Database\Seeders\Rating\RatingAttachmentSeeder::class,
            \Database\Seeders\Rating\RatingReplySeeder::class,

            // ---------------------------------------------------------
            // REPORTS
            // ---------------------------------------------------------
            \Database\Seeders\Report\ReportCategorySeeder::class,
            \Database\Seeders\Report\ReportSeeder::class,
            \Database\Seeders\Report\ReportAttachmentSeeder::class,
            \Database\Seeders\Report\ReportStatusHistorySeeder::class,
            \Database\Seeders\Report\ReportReplySeeder::class,

            // ---------------------------------------------------------
            // CONVERSATIONS & MESSAGES
            // ---------------------------------------------------------
            \Database\Seeders\Conversation\ConversationSeeder::class,
            \Database\Seeders\Conversation\MessageSeeder::class,

            // ---------------------------------------------------------
            // ACTIVITY & LOGS
            // ---------------------------------------------------------
            \Database\Seeders\Unit\UnitVisitSeeder::class,
            \Database\Seeders\Notification\NotificationSeeder::class,
            \Database\Seeders\Moderation\ModerationLogSeeder::class,
            \Database\Seeders\Export\ExportSeeder::class,
        ]);
    }
}