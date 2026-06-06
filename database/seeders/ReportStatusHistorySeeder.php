<?php

namespace Database\Seeders;

use App\Models\Reports\Report;
use App\Models\Reports\ReportStatusHistory;
use Illuminate\Database\Seeder;

class ReportStatusHistorySeeder extends Seeder
{
    public function run(): void
    {
        $reports = Report::all();

        foreach ($reports as $report) {
            $statuses = ['new', 'assigned', 'in_progress', 'replied', 'resolved'];
            $currentIndex = array_search($report->status, $statuses);

            if ($currentIndex === false) {
                $currentIndex = 0;
            }

            for ($i = 0; $i <= $currentIndex; $i++) {
                ReportStatusHistory::factory()
                    ->forReport($report)
                    ->withTransition(
                        $i > 0 ? $statuses[$i - 1] : 'new',
                        $statuses[$i]
                    )
                    ->create();
            }
        }
    }
}