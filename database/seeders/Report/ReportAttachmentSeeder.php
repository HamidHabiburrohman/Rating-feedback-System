<?php

namespace Database\Seeders\Report;

use App\Models\Report\Report;
use App\Models\Report\ReportAttachment;
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