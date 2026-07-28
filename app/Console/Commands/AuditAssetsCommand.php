<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AuditAssetsCommand extends Command
{
    use \App\Console\Traits\AuditsAssets;

    protected $signature = 'project:audit-assets';
    protected $description = 'Scans Blade, CSS, JS, and Images to generate a comprehensive asset dependency report.';

    public function handle(): int
    {
        $this->info('🔍 Auditing Asset Dependencies...');
        $this->newLine();

        $data = $this->categorizeAssets();

        $this->table(
            ['Category', 'Count', 'Status'],
            [
                ['USED', count($data['used']), $this->formatStatus('OK')],
                ['UNUSED', count($data['unused']), count($data['unused']) > 0 ? $this->formatStatus('WARN') : $this->formatStatus('OK')],
                ['LEGACY', count($data['legacy']), count($data['legacy']) > 0 ? $this->formatStatus('DANGER') : $this->formatStatus('OK')],
                ['ORPHAN', count($data['orphans']), count($data['orphans']) > 0 ? $this->formatStatus('WARN') : $this->formatStatus('OK')],
                ['DUPLICATE GROUPS', count($data['duplicates']), count($data['duplicates']) > 0 ? $this->formatStatus('WARN') : $this->formatStatus('OK')],
            ]
        );

        if (count($data['legacy']) > 0) {
            $this->newLine();
            $this->error('⚠️  Legacy Files Detected:');
            foreach (array_slice($data['legacy'], 0, 5) as $file) {
                $this->line("   - {$file}");
            }
            if (count($data['legacy']) > 5) $this->line('   ... and ' . (count($data['legacy']) - 5) . ' more.');
        }

        return self::SUCCESS;
    }

    private function formatStatus(string $type): string
    {
        return match ($type) {
            'OK' => '<fg=green>● HEALTHY</>',
            'WARN' => '<fg=yellow>● ATTENTION</>',
            'DANGER' => '<fg=red>● CRITICAL</>',
            default => $type,
        };
    }
}