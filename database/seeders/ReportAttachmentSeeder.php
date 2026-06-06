<?php

namespace Database\Seeders;

use App\Models\Reports\Report;
use App\Models\Reports\ReportAttachment;
use Illuminate\Database\Seeder;

class ReportAttachmentSeeder extends Seeder
{
    public function run(): void
    {
        $reports = Report::all();

        foreach ($reports as $report) {
            $attachmentCount = rand(1, 3);

            for ($i = 0; $i < $attachmentCount; $i++) {
                ReportAttachment::factory()
                    ->forReport($report)
                    ->withSortOrder($i)
                    ->create();
            }
        }
    }
}