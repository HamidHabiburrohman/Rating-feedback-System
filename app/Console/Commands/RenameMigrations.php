<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RenameMigrations extends Command
{
    protected $signature = 'migrations:rename
                            {--dry-run : Preview changes without executing}
                            {--force : Skip confirmation prompt}';

    protected $description = 'Rename migration files to use 001, 002, 003 format without create_ prefix';

    protected string $migrationsPath;
    protected array $actions = [];
    protected bool $dryRun;

    public function handle(): int
    {
        $this->dryRun = $this->option('dry-run');
        $this->migrationsPath = database_path('migrations');

        $this->displayHeader();

        if (!$this->confirmExecution()) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $this->processAllFolders();
        $this->displaySummary();

        return 0;
    }

    protected function displayHeader(): void
    {
        $this->newLine();
        $this->line('==================================================');
        $this->line('  MIGRATION FILES RENAMER');
        $this->line('==================================================');
        $this->newLine();

        if ($this->dryRun) {
            $this->warn('DRY RUN MODE - No files will be modified');
            $this->newLine();
        }
    }

    protected function confirmExecution(): bool
    {
        if ($this->option('force') || $this->dryRun) {
            return true;
        }

        $this->info('This command will:');
        $this->line('  - Rename migration files to 001, 002, 003 format');
        $this->line('  - Remove create_ prefix from filenames');
        $this->line('  - Maintain proper dependency ordering');
        $this->newLine();

        return $this->confirm('Do you want to continue?', true);
    }

    protected function processAllFolders(): void
    {
        $folders = [
            'authentication',
            'units',
            'employees',
            'feedback',
            'reports',
            'system',
            'infrastructure'
    ];

        foreach ($folders as $folder) {
            $this->processFolder($folder);
        }
    }

    protected function processFolder(string $folder): void
    {
        $folderPath = $this->migrationsPath . '/' . $folder;

        if (!File::isDirectory($folderPath)) {
            $this->recordAction('SKIP', "Folder not found: {$folder}");
            return;
        }

        $files = File::files($folderPath);

        if (empty($files)) {
            $this->recordAction('SKIP', "No files in folder: {$folder}");
            return;
        }

        $this->info("Processing folder: {$folder}");

        $migrations = $this->parseMigrationFiles($files);
        $this->sortMigrationsByDependency($migrations);
        $this->renameMigrations($migrations, $folder);

        $this->newLine();
    }

    protected function parseMigrationFiles(array $files): array
    {
        $migrations = [];

        foreach ($files as $file) {
            $filename = $file->getFilename();

            if (preg_match('/^\d{4}_\d{2}_\d{2}_\d{6}_(.+)\.php$/', $filename, $matches)) {
                $tableName = $matches[1];
                $tableName = preg_replace('/^create_/', '', $tableName);
                $tableName = preg_replace('/_table$/', '', $tableName);

                $migrations[] = [
                    'original_path' => $file->getPathname(),
                    'original_name' => $filename,
                    'table_name' => $tableName,
                    'new_name' => null,
                    'order' => 0
    ];
            } elseif (preg_match('/^(\d{3})_(.+)\.php$/', $filename, $matches)) {
                $order = (int)$matches[1];
                $tableName = $matches[2];

                $migrations[] = [
                    'original_path' => $file->getPathname(),
                    'original_name' => $filename,
                    'table_name' => $tableName,
                    'new_name' => null,
                    'order' => $order
    ];
            }
        }

        return $migrations;
    }

    protected function sortMigrationsByDependency(array &$migrations): void
    {
        $tableNames = array_column($migrations, 'table_name');

        foreach ($migrations as &$migration) {
            $content = File::get($migration['original_path']);

            $migration['depends_on'] = [];

            if (preg_match_all('/->constrained\([\'"]([^\'"]+)[\'"]\)/', $content, $matches)) {
                foreach ($matches[1] as $referencedTable) {
                    if (in_array($referencedTable, $tableNames)) {
                        $migration['depends_on'][] = $referencedTable;
                    }
                }
            }

            if (preg_match_all('/foreignId\([\'"]([^\'"]+)[\'"]\)/', $content, $matches)) {
                foreach ($matches[1] as $column) {
                    $referencedTable = Str::plural(str_replace('_id', '', $column));
                    if (in_array($referencedTable, $tableNames)) {
                        $migration['depends_on'][] = $referencedTable;
                    }
                }
            }
        }

        $sorted = [];
        $remaining = $migrations;

        while (!empty($remaining)) {
            $added = false;

            foreach ($remaining as $index => $migration) {
                $dependenciesMet = true;

                foreach ($migration['depends_on'] as $dep) {
                    $found = false;
                    foreach ($sorted as $s) {
                        if ($s['table_name'] === $dep) {
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        $dependenciesMet = false;
                        break;
                    }
                }

                if ($dependenciesMet) {
                    $sorted[] = $migration;
                    unset($remaining[$index]);
                    $remaining = array_values($remaining);
                    $added = true;
                    break;
                }
            }

            if (!$added) {
                $sorted[] = $remaining[0];
                unset($remaining[0]);
                $remaining = array_values($remaining);
            }
        }

        $migrations = $sorted;
    }

    protected function renameMigrations(array $migrations, string $folder): void
    {
        foreach ($migrations as $index => &$migration) {
            $newOrder = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $newName = "{$newOrder}_{$migration['table_name']}.php";

            $migration['new_name'] = $newName;
            $migration['order'] = $index + 1;

            $oldPath = $migration['original_path'];
            $newPath = dirname($oldPath) . '/' . $newName;

            if ($oldPath === $newPath) {
                $this->recordAction('SKIP', "{$folder}/{$migration['original_name']} (already correct)");
                continue;
            }

            if (File::exists($newPath)) {
                $this->recordAction('SKIP', "{$folder}/{$newName} (target exists)");
                continue;
            }

            if ($this->dryRun) {
                $this->recordAction('RENAME', "{$folder}/{$migration['original_name']} -> {$newName}");
                continue;
            }

            File::move($oldPath, $newPath);
            $this->recordAction('RENAME', "{$folder}/{$migration['original_name']} -> {$newName}");
        }
    }

    protected function recordAction(string $type, string $description): void
    {
        $this->actions[] = ['type' => $type, 'description' => $description];

        $colors = [
            'RENAME' => 'green',
            'SKIP' => 'yellow'
    ];

        $color = $colors[$type] ?? 'white';
        $tag = str_pad("[{$type}]", 10);
        $this->line("  <fg={$color}>{$tag}</> {$description}");
    }

    protected function displaySummary(): void
    {
        $this->newLine();
        $this->line('==================================================');
        $this->line('  SUMMARY');
        $this->line('==================================================');
        $this->newLine();

        $summary = [];
        foreach ($this->actions as $action) {
            $type = $action['type'];
            $summary[$type] = ($summary[$type] ?? 0) + 1;
        }

        $table = [];
        foreach ($summary as $type => $count) {
            $table[] = [$type, $count];
        }

        $this->table(['Action', 'Count'], $table);
        $this->newLine();

        if ($this->dryRun) {
            $this->warn('This was a dry run. No files were modified.');
            $this->line('Run without --dry-run to apply changes.');
        } else {
            $this->info('Migration files renamed successfully.');
            $this->line('Run: php artisan migrate:ordered --fresh --seed');
        }

        $this->newLine();
    }
}