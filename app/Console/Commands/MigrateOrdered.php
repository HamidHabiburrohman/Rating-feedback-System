<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateOrdered extends Command
{
    protected $signature = 'migrate:ordered
                            {--fresh : Drop all tables and re-run all migrations}
                            {--seed : Run database seeders after migration}
                            {--force : Force the operation to run in production}
                            {--step : Force the migrations to be run so they can be rolled back individually}';

    protected $description = 'Run all migrations from root migrations folder in sequential order';

    public function handle(): int
    {
        $this->line('==================================================');
        $this->line('  ORDERED MIGRATION RUNNER');
        $this->line('==================================================');
        $this->newLine();

        if (app()->environment('production') && !$this->option('force')) {
            if (!$this->confirm('Application is in production. Do you wish to continue?')) {
                $this->info('Migration cancelled.');
                return 0;
            }
        }

        $params = ['--force' => true];

        if ($this->option('step')) {
            $params['--step'] = true;
        }

        if ($this->option('fresh')) {
            $this->warn('Running fresh migration - all tables will be dropped!');
            $this->newLine();

            $this->call('migrate:fresh', $params);
        } else {
            $this->info('Running migration...');
            $this->newLine();

            $this->call('migrate', $params);
        }

        if ($this->option('seed')) {
            $this->newLine();
            $this->info('Running database seeders...');
            $this->newLine();

            $this->call('db:seed', ['--force' => true]);
        }

        $this->newLine();
        $this->info('Migration process completed successfully.');

        return 0;
    }
}