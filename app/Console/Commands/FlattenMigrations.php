<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FlattenMigrations extends Command
{
    protected $signature = 'migrations:flatten
                            {--dry-run : Preview changes without executing}
                            {--force : Skip confirmation prompt}';

    protected $description = 'Move all migrations from subfolders to root migrations folder with sequential numbering';

    protected string $migrationsPath;
    protected string $backupPath;
    protected array $actions = [];
    protected bool $dryRun;

    protected array $folderPriority = [
        'authentication',
        'units',
        'employees',
        'feedback',
        'reports',
        'system',
        'infrastructure',
    ];

    public function handle(): int
    {
        $this->dryRun = $this->option('dry-run');
        $this->migrationsPath = database_path('migrations');
        $this->backupPath = database_path('migrations_backup_' . date('Y-m-d_His'));

        $this->line('==================================================');
        $this->line('  FLATTEN MIGRATIONS');
        $this->line('==================================================');
        $this->newLine();

        if ($this->dryRun) {
            $this->warn('DRY RUN MODE - No files will be modified');
            $this->newLine();
        }

        if (!$this->confirmExecution()) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $this->backupMigrations();
        $this->flattenAll();

        $this->displaySummary();

        return 0;
    }

    protected function confirmExecution(): bool
    {
        if ($this->option('force') || $this->dryRun) {
            return true;
        }

        $this->info('This command will:');
        $this->line('  - Backup current migrations folder');
        $this->line('  - Move all migrations to root migrations/ folder');
        $this->line('  - Rename files to 001, 002, 003 format');
        $this->line('  - Remove subfolders');
        $this->newLine();

        return $this->confirm('Do you want to continue?', true);
    }

    protected function backupMigrations(): void
    {
        if ($this->dryRun) {
            $this->recordAction('BACKUP', "Would backup to: migrations_backup_" . basename($this->backupPath));
            return;
        }

        File::copyDirectory($this->migrationsPath, $this->backupPath);
        $this->recordAction('BACKUP', 'Migrations backed up successfully');
    }

    protected function flattenAll(): void
    {
        $orderedFiles = $this->collectOrderedFiles();

        $this->info(sprintf('Found %d migration files to process', count($orderedFiles)));
        $this->newLine();

        foreach ($orderedFiles as $index => $fileInfo) {
            $newNumber = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $newName = "{$newNumber}_{$fileInfo['table_name']}.php";
            $targetPath = $this->migrationsPath . '/' . $newName;

            if ($fileInfo['path'] === $targetPath) {
                $this->recordAction('SKIP', "{$newName} (already correct)");
                continue;
            }

            if ($this->dryRun) {
                $this->recordAction('MOVE', "{$fileInfo['relative']} -> {$newName}");
                continue;
            }

            File::copy($fileInfo['path'], $targetPath);
            $this->recordAction('MOVE', "{$fileInfo['relative']} -> {$newName}");
        }

        if (!$this->dryRun) {
            $this->cleanupSubfolders();
            $this->cleanupOldRootFiles($orderedFiles);
        }
    }

    protected function collectOrderedFiles(): array
    {
        $orderedFiles = [];

        foreach ($this->folderPriority as $folder) {
            $folderPath = $this->migrationsPath . '/' . $folder;

            if (!File::isDirectory($folderPath)) {
                continue;
            }

            $files = File::files($folderPath);
            usort($files, function ($a, $b) {
                return strcmp($a->getFilename(), $b->getFilename());
            });

            foreach ($files as $file) {
                $tableName = $this->extractTableName($file->getFilename());

                if (!$tableName) {
                    continue;
                }

                $orderedFiles[] = [
                    'path' => $file->getPathname(),
                    'relative' => "{$folder}/{$file->getFilename()}",
                    'table_name' => $tableName,
                ];
            }
        }

        $rootFiles = File::files($this->migrationsPath);
        foreach ($rootFiles as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $tableName = $this->extractTableName($file->getFilename());

            if (!$tableName) {
                continue;
            }

            $alreadyCollected = false;
            foreach ($orderedFiles as $existing) {
                if ($existing['table_name'] === $tableName) {
                    $alreadyCollected = true;
                    break;
                }
            }

            if (!$alreadyCollected) {
                $orderedFiles[] = [
                    'path' => $file->getPathname(),
                    'relative' => $file->getFilename(),
                    'table_name' => $tableName,
                ];
            }
        }

        return $orderedFiles;
    }

    protected function extractTableName(string $filename): ?string
    {
        if (preg_match('/^\d{4}_\d{2}_\d{2}_\d{6}_(.+)\.php$/', $filename, $matches)) {
            $tableName = $matches[1];
            $tableName = preg_replace('/^create_/', '', $tableName);
            $tableName = preg_replace('/_table$/', '', $tableName);
            return $tableName;
        }

        if (preg_match('/^(\d{3})_(.+)\.php$/', $filename, $matches)) {
            return $matches[2];
        }

        return null;
    }

    protected function cleanupSubfolders(): void
    {
        foreach ($this->folderPriority as $folder) {
            $folderPath = $this->migrationsPath . '/' . $folder;

            if (!File::isDirectory($folderPath)) {
                continue;
            }

            File::deleteDirectory($folderPath);
            $this->recordAction('DELETE FOLDER', $folder);
        }
    }

    protected function cleanupOldRootFiles(array $orderedFiles): void
    {
        $validTableNames = array_column($orderedFiles, 'table_name');

        $rootFiles = File::files($this->migrationsPath);
        foreach ($rootFiles as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $tableName = $this->extractTableName($file->getFilename());

            if (!$tableName) {
                continue;
            }

            $expectedName = null;
            foreach ($orderedFiles as $index => $info) {
                if ($info['table_name'] === $tableName) {
                    $expectedName = str_pad($index + 1, 3, '0', STR_PAD_LEFT) . '_' . $tableName . '.php';
                    break;
                }
            }

            if ($expectedName && $file->getFilename() !== $expectedName) {
                File::delete($file->getPathname());
                $this->recordAction('DELETE OLD', $file->getFilename());
            }
        }
    }

    protected function recordAction(string $type, string $description): void
    {
        $this->actions[] = ['type' => $type, 'description' => $description];

        $colors = [
            'MOVE' => 'green',
            'DELETE FOLDER' => 'red',
            'DELETE OLD' => 'yellow',
            'BACKUP' => 'cyan',
            'SKIP' => 'gray',
        ];

        $color = $colors[$type] ?? 'white';
        $tag = str_pad("[{$type}]", 16);
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
            $this->info('Migrations flattened successfully.');
            $this->line('Next: php artisan migrate:fresh --seed');
        }

        $this->newLine();
    }
}