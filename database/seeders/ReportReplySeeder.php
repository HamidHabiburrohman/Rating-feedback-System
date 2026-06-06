<?php

namespace Database\Seeders;

use App\Models\Reports\Report;
use App\Models\Reports\ReportReply;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Admin;
use Illuminate\Database\Seeder;

class ReportReplySeeder extends Seeder
{
    public function run(): void
    {
        $reports = Report::whereIn('status', ['in_progress', 'replied', 'resolved'])->get();
        $employees = Employee::all();
        $admins = Admin::all();

        foreach ($reports as $report) {
            if (rand(0, 1)) {
                $isEmployee = rand(0, 1);

                if ($isEmployee && $employees->count() > 0) {
                    ReportReply::factory()
                        ->forReport($report)
                        ->byEmployee($employees->random())
                        ->create();
                } elseif ($admins->count() > 0) {
                    ReportReply::factory()
                        ->forReport($report)
                        ->byAdmin($admins->random())
                        ->create();
                }
            }
        }
    }
}