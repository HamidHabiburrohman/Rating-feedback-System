<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixDashboardAndReportSeeder extends Command
{
    protected $signature = 'fix:dashboard-and-report';
    protected $description = 'Automatically fix DashboardService stdClass error and ReportSeeder deprecated columns';

    public function handle(): int
    {
        $this->info('🔧 Starting automatic fixes...');
        $this->newLine();

        $this->fixDashboardService();
        $this->fixReportFactory();
        $this->fixReportModel();
        $this->fixReportObserver();

        $this->newLine();
        $this->info('✅ All fixes applied successfully!');
        $this->newLine();
        $this->comment('You can now run: php artisan db:seed --class=Database\\Seeders\\Report\\ReportSeeder');

        return self::SUCCESS;
    }

    protected function fixDashboardService(): void
    {
        $path = app_path('Services/Admin/Dashboard/DashboardService.php');

        if (!File::exists($path)) {
            $this->warn("⚠ DashboardService.php not found at: {$path}");
            return;
        }

        $content = File::get($path);
        $original = $content;

        $content = preg_replace(
            '/DB::table\s*\(\s*[\'"]employees[\'"]\s*\)/',
            '\\App\\Models\\Authentication\\Employee::query()',
            $content
        );

        $content = preg_replace(
            '/\$emp->unitAssignments\s*->\s*where\s*\(\s*[\'"]is_active[\'"]\s*,\s*true\s*\)/',
            '$emp->unitAssignments()->whereIn(\'status\', [\'assigned\', \'accepted\', \'in_progress\', \'waiting_verification\'])',
            $content
        );

        $content = preg_replace(
            '/\$emp->unitAssignments\s*\(\s*\)\s*->\s*where\s*\(\s*[\'"]is_active[\'"]\s*,\s*true\s*\)/',
            '$emp->unitAssignments()->whereIn(\'status\', [\'assigned\', \'accepted\', \'in_progress\', \'waiting_verification\'])',
            $content
        );

        $content = preg_replace(
            '/Employee::where\s*\(\s*[\'"]is_active[\'"]\s*,\s*true\s*\)/',
            'Employee::where(\'is_active\', true)',
            $content
        );

        if ($content !== $original) {
            File::put($path, $content);
            $this->info("✓ Fixed: DashboardService.php");
        } else {
            $this->line("  (no changes needed in DashboardService.php)");
        }
    }

    protected function fixReportFactory(): void
    {
        $path = database_path('factories/Report/ReportFactory.php');

        if (!File::exists($path)) {
            $this->warn("⚠ ReportFactory.php not found at: {$path}");
            return;
        }

        $content = File::get($path);
        $original = $content;

        $deprecatedColumns = [
            '/[\'"]admin_id[\'"]\s*=>\s*[^,]+,?\s*/',
            '/[\'"]admin_response[\'"]\s*=>\s*[^,]+,?\s*/',
            '/[\'"]replied_at[\'"]\s*=>\s*[^,]+,?\s*/',
            '/[\'"]assigned_to_employee_id[\'"]\s*=>\s*[^,]+,?\s*/',
        ];

        foreach ($deprecatedColumns as $pattern) {
            $content = preg_replace($pattern, '', $content);
        }

        $content = preg_replace(
            '/[\'"]admin_response\.max[\'"]\s*=>\s*[^,]+,?\s*/',
            '',
            $content
        );

        $content = preg_replace('/,\s*,/', ',', $content);
        $content = preg_replace('/,\s*\]/', "\n        ]", $content);
        $content = preg_replace('/,\s*\)/', "\n        )", $content);

        if ($content !== $original) {
            File::put($path, $content);
            $this->info("✓ Fixed: ReportFactory.php (removed deprecated columns)");
        } else {
            $this->line("  (no changes needed in ReportFactory.php)");
        }
    }

    protected function fixReportModel(): void
    {
        $path = app_path('Models/Report/Report.php');

        if (!File::exists($path)) {
            $this->warn("⚠ Report.php not found at: {$path}");
            return;
        }

        $content = File::get($path);
        $original = $content;

        $content = preg_replace("/[\'\"]admin_id[\'\"],?\s*/", '', $content);
        $content = preg_replace("/[\'\"]admin_response[\'\"],?\s*/", '', $content);
        $content = preg_replace("/[\'\"]replied_at[\'\"],?\s*/", '', $content);
        $content = preg_replace("/[\'\"]assigned_to_employee_id[\'\"],?\s*/", '', $content);

        $content = preg_replace(
            '/public\s+function\s+assignedToEmployee\s*\(\s*\)\s*\{[^}]+\}/s',
            '',
            $content
        );

        $content = preg_replace(
            '/public\s+function\s+admin\s*\(\s*\)\s*\{[^}]+\}/s',
            '',
            $content
        );

        $content = preg_replace('/,\s*,/', ',', $content);

        if ($content !== $original) {
            File::put($path, $content);
            $this->info("✓ Fixed: Report.php (removed deprecated fillable & relations)");
        } else {
            $this->line("  (no changes needed in Report.php)");
        }
    }

    protected function fixReportObserver(): void
    {
        $path = app_path('Observers/ReportObserver.php');

        if (!File::exists($path)) {
            $this->line("  (ReportObserver.php not found, skipping)");
            return;
        }

        $content = File::get($path);
        $original = $content;

        $content = preg_replace(
            '/->where\s*\(\s*[\'"]is_active[\'"]\s*,\s*true\s*\)/',
            '->whereIn(\'status\', [\'assigned\', \'accepted\', \'in_progress\', \'waiting_verification\'])',
            $content
        );

        if ($content !== $original) {
            File::put($path, $content);
            $this->info("✓ Fixed: ReportObserver.php");
        } else {
            $this->line("  (no changes needed in ReportObserver.php)");
        }
    }
}