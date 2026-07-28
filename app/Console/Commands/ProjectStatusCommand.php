<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProjectStatusCommand extends Command
{
    use \App\Console\Traits\AuditsAssets;

    protected $signature = 'project:status';
    protected $description = 'Generates a comprehensive project health and infrastructure report.';

    public function handle(): int
    {
        $this->newLine();
        $this->info('🏥 ==========================================');
        $this->info('🏥 RATE UNIT SYSTEM - PROJECT HEALTH REPORT');
        $this->info('🏥 ==========================================');
        $this->newLine();

        $data = $this->categorizeAssets();
        $score = 100;
        $penalties = [];

        // 1. Infrastructure & Role Structure
        $this->info('📁 1. ROLE STRUCTURE & INFRASTRUCTURE');
        $roles = ['admin', 'employee', 'student', 'common', 'vendor'];
        foreach ($roles as $role) {
            $exists = File::isDirectory(public_path("assets/{$role}"));
            if ($exists) {
                $this->line("   <fg=green>✔</> {$role}/");
            } else {
                $this->line("   <fg=red>✘</> {$role}/ (Missing)");
                $score -= 5;
                $penalties[] = "Missing role folder: {$role}";
            }
        }
        $this->newLine();

        // 2. Modules Overview
        $this->info('📦 2. MODULES OVERVIEW');
        $modules = ['conversations', 'dashboard', 'settings'];
        foreach ($modules as $mod) {
            $cssCount = count(File::glob(public_path("assets/admin/css/{$mod}/*.css")));
            $jsCount = count(File::glob(public_path("assets/admin/js/{$mod}/*.js")));
            $this->line("   <fg=cyan>▸</> {$mod}: {$cssCount} CSS, {$jsCount} JS");
        }
        $this->newLine();

        // 3. Asset Health
        $this->info('🧹 3. ASSET HEALTH');
        $this->line("   Used Assets      : <fg=green>" . count($data['used']) . "</>");
        $this->line("   Unused Assets    : <fg=yellow>" . count($data['unused']) . "</>");
        $this->line("   Legacy Assets    : <fg=red>" . count($data['legacy']) . "</>");
        $this->line("   Orphan Assets    : <fg=yellow>" . count($data['orphans']) . "</>");
        $this->line("   Duplicate Groups : <fg=yellow>" . count($data['duplicates']) . "</>");
        $this->newLine();

        // Calculate Penalties
        if (count($data['legacy']) > 0) {
            $penalty = min(20, count($data['legacy']));
            $score -= $penalty;
            $penalties[] = "Legacy assets detected (-{$penalty})";
        }
        if (count($data['unused']) > 0) {
            $penalty = min(20, (int)(count($data['unused']) * 0.5));
            $score -= $penalty;
            $penalties[] = "Unused assets detected (-{$penalty})";
        }
        if (count($data['orphans']) > 0) {
            $penalty = min(10, count($data['orphans']) * 2);
            $score -= $penalty;
            $penalties[] = "Orphan assets in root public/ (-{$penalty})";
        }

        $score = max(0, $score);

        // 4. Overall Health Score
        $this->info('📊 4. OVERALL HEALTH SCORE');
        $color = $score >= 80 ? 'green' : ($score >= 50 ? 'yellow' : 'red');
        $this->line("   Score: <fg={$color};options=bold>{$score}/100</>");
        
        if (!empty($penalties)) {
            $this->newLine();
            $this->error('   ⚠️  Deductions:');
            foreach ($penalties as $p) {
                $this->line("      - {$p}");
            }
        }

        $this->newLine();
        $this->info('🏥 ==========================================');
        $this->newLine();

        return self::SUCCESS;
    }
}