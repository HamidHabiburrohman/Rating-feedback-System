<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class MigrateOrdered extends Command
{
    protected $signature = 'migrate:ordered 
                            {--fresh : Drop all tables first}
                            {--seed : Run seeders after migration}';

    protected $description = 'Run migrations in correct order from subfolder structure';

    protected array $order = [];

    public function __construct()
    {
        parent::__construct();
        $this->defineOrder();
    }

    protected function defineOrder(): void
    {
        $this->order = [
            'infrastructure/cache.php',
            'infrastructure/jobs.php',
            'authentication/admins.php',
            'authentication/password_reset_tokens.php',
            'authentication/sessions.php',
            'authentication/students.php',
            'authentication/employees.php',
            'units/unit_types.php',
            'units/unit_departments.php',
            'units/facilities.php',
            'units/qr_codes.php',
            'units/units.php',
            'units/unit_facilities.php',
            'units/unit_photos.php',
            'feedback/rating_categories.php',
            'feedback/unit_visits.php',
            'feedback/ratings.php',
            'feedback/rating_scores.php',
            'feedback/rating_attachments.php',
            'feedback/rating_replies.php',
            'reports/report_categories.php',
            'reports/reports.php',
            'reports/report_attachments.php',
            'reports/report_replies.php',
            'reports/report_status_histories.php',
            'employees/employee_positions.php',
            'employees/employee_unit_assignments.php',
            'system/notifications.php',
            'system/settings.php',
            'system/exports.php',
            'system/moderation_logs.php',
        ];
    }

    public function handle(): int
    {
        $this->info('Running ordered migrations...');

        $migrationPath = database_path('migrations');

        if ($this->option('fresh')) {
            if (!$this->confirm('This will DROP ALL TABLES. Continue?', false)) {
                return Command::FAILURE;
            }
            $this->dropAllTables();
        }

        $executed = [];

        foreach ($this->order as $relativePath) {
            $fullPath = $migrationPath . '/' . $relativePath;

            if (!File::exists($fullPath)) {
                $this->warn("Skipping (not found): {$relativePath}");
                continue;
            }

            $this->info("Migrating: {$relativePath}");

            try {
                $migration = require $fullPath;
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                $migration->up();
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                $executed[] = $relativePath;
                $this->line("  Completed");
            } catch (\Exception $e) {
                $this->error("  Failed: " . $e->getMessage());
                DB::statement('SET FOREIGN_KEY_CHECKS=1');

                if ($this->confirm('Migration failed. Rollback and stop?', true)) {
                    $this->rollbackExecuted($executed, $migrationPath);
                    return Command::FAILURE;
                }
            }
        }

        $this->info("\nSuccessfully migrated " . count($executed) . " tables.");

        if ($this->option('seed')) {
            $this->info('Running seeders...');
            $this->call('db:seed', ['--force' => true]);
        }

        return Command::SUCCESS;
    }

    protected function dropAllTables(): void
    {
        $this->info('Dropping all tables...');

        $tables = DB::select('SHOW TABLES');
        $dbName = env('DB_DATABASE');
        $column = "Tables_in_{$dbName}";

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tables as $table) {
            $tableName = $table->$column;
            Schema::dropIfExists($tableName);
            $this->line("  Dropped: {$tableName}");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->info('All tables dropped.');
    }

    protected function rollbackExecuted(array $executed, string $migrationPath): void
    {
        $this->warn('Rolling back executed migrations...');

        foreach (array_reverse($executed) as $relativePath) {
            $fullPath = $migrationPath . '/' . $relativePath;
            if (File::exists($fullPath)) {
                try {
                    $migration = require $fullPath;
                    DB::statement('SET FOREIGN_KEY_CHECKS=0');
                    $migration->down();
                    DB::statement('SET FOREIGN_KEY_CHECKS=1');
                    $this->line("  Rolled back: {$relativePath}");
                } catch (\Exception $e) {
                    $this->error("  Failed rollback: {$relativePath}");
                }
            }
        }
    }
}