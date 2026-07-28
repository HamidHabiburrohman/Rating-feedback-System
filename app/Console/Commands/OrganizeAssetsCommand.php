<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OrganizeAssetsCommand extends Command
{
    protected $signature = 'project:organize-assets {--execute : Execute the recommended structural changes}';

    protected $description = 'Inspects and organizes the public asset structure into a clean role-based architecture.';

    protected array $moves = [
        'libs' => 'common/vendor/libs',
        'vendor' => 'common/vendor/vendor',
        'components' => 'common/components',
        'shared' => 'common/shared',
        'css/icons' => 'common/css/icons',
        'images' => 'common/images',
        'landing' => 'common/landing',
    ];

    public function handle(): int
    {
        $this->info('📂 Scanning Public Asset Structure...');
        $this->newLine();

        $isExecuting = $this->option('execute');
        $base = public_path('assets');
        $actions = 0;

        foreach ($this->moves as $from => $to) {
            $fromPath = $base . '/' . $from;
            $toPath = $base . '/' . $to;

            if (File::exists($fromPath) || File::isDirectory($fromPath)) {
                if ($isExecuting) {
                    $this->safeMove($fromPath, $toPath);
                    $this->info("✅ Moved: {$from} -> {$to}");
                } else {
                    $this->warn("⚠️  Recommended Move: {$from} -> {$to}");
                }
                $actions++;
            }
        }

        $this->newLine();
        if ($actions === 0) {
            $this->info('✨ Asset structure is already normalized.');
        } elseif (!$isExecuting) {
            $this->info('ℹ️  Dry-run mode. Run with --execute to apply changes.');
        } else {
            $this->info('🎉 Asset structure successfully normalized.');
        }

        return self::SUCCESS;
    }

    protected function safeMove(string $from, string $to): void
    {
        if (!File::exists($from) && !File::isDirectory($from)) {
            return;
        }

        File::ensureDirectoryExists(dirname($to));

        if (File::isDirectory($from)) {
            if (File::isDirectory($to)) {
                File::copyDirectory($from, $to);
                File::deleteDirectory($from);
            } else {
                File::moveDirectory($from, $to);
            }
        } else {
            if (File::exists($to)) {
                File::delete($to);
            }
            File::move($from, $to);
        }
    }
}