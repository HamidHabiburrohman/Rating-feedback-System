<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanupAssetsCommand extends Command
{
    use \App\Console\Traits\AuditsAssets;

    protected $signature = 'project:cleanup-assets';
    protected $description = 'Deletes confirmed unused and legacy assets after user confirmation.';

    public function handle(): int
    {
        $this->info('🧹 Preparing Asset Cleanup...');
        $data = $this->categorizeAssets();

        $toDelete = array_merge($data['unused'], $data['legacy']);
        
        if (empty($toDelete)) {
            $this->info('✨ No unused or legacy assets found. Workspace is clean.');
            return self::SUCCESS;
        }

        $this->warn('⚠️  The following ' . count($toDelete) . ' files/directories will be DELETED:');
        foreach (array_slice($toDelete, 0, 10) as $file) {
            $this->line("   - {$file}");
        }
        if (count($toDelete) > 10) {
            $this->line('   ... and ' . (count($toDelete) - 10) . ' more.');
        }

        if (!$this->confirm('Are you absolutely sure you want to delete these assets? This action cannot be undone.', false)) {
            $this->info('❌ Cleanup aborted.');
            return self::SUCCESS;
        }

        $deleted = 0;
        foreach ($toDelete as $path) {
            $fullPath = public_path('assets/' . $path);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
                $deleted++;
            } elseif (File::isDirectory($fullPath)) {
                File::deleteDirectory($fullPath);
                $deleted++;
            }
        }

        $this->newLine();
        $this->info("✅ Successfully deleted {$deleted} unused/legacy assets.");

        return self::SUCCESS;
    }
}