<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class FixAppStructure extends Command
{
    protected $signature = 'structure:fix 
                            {--dry-run : Only show what would be changed}
                            {--force : Skip confirmation}';

    protected $description = 'Fix folder casing and naming in app/Models to follow PSR-4';

    protected Filesystem $files;

    protected array $mappings = [
        'authentication' => 'Authentication',
        'employees' => 'Employee',
        'feedback' => 'Feedback',
        'reports' => 'Report',
        'system' => 'System',
        'units' => 'Unit',
    ];

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $this->info('Fixing app/Models folder structure...');

        $basePath = app_path('Models');

        $changes = [];

        foreach ($this->mappings as $oldName => $newName) {
            $oldPath = $basePath . '/' . $oldName;
            $newPath = $basePath . '/' . $newName;

            if (!is_dir($oldPath)) {
                continue;
            }

            $changes[] = [
                'old' => $oldName,
                'new' => $newName,
                'path' => $oldPath,
            ];
        }

        if (empty($changes)) {
            $this->info('No structural changes needed.');
            return Command::SUCCESS;
        }

        $this->table(['Current Folder', 'New Folder'], array_map(fn($c) => [$c['old'], $c['new']], $changes));

        if (!$this->option('dry-run') && ($this->option('force') || $this->confirm('Apply these changes? This will rename folders and update namespace in model files.'))) {
            foreach ($changes as $change) {
                $this->renameFolderAndUpdateNamespace($change['path'], $change['new']);
            }
            $this->info('Structure fixed.');
        } else {
            $this->info('Dry run completed. No changes applied.');
        }

        return Command::SUCCESS;
    }

    protected function renameFolderAndUpdateNamespace(string $oldPath, string $newFolderName): void
    {
        $parentDir = dirname($oldPath);
        $newPath = $parentDir . '/' . $newFolderName;

        // Get all PHP files in the folder
        $files = File::glob($oldPath . '/*.php');

        foreach ($files as $file) {
            $content = File::get($file);
            $oldNamespace = 'namespace App\\Models\\' . basename($oldPath) . ';';
            $newNamespace = 'namespace App\\Models\\' . $newFolderName . ';';
            $newContent = str_replace($oldNamespace, $newNamespace, $content);
            File::put($file, $newContent);
            $this->line("Updated namespace in " . basename($file));
        }

        // Rename folder
        File::move($oldPath, $newPath);
        $this->info("Renamed folder: " . basename($oldPath) . " -> " . $newFolderName);
    }
}