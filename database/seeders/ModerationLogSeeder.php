<?php

namespace Database\Seeders;

use App\Models\ModerationLog;
use Illuminate\Database\Seeder;

class ModerationLogSeeder extends Seeder
{
    public function run(): void
    {
        $ratingsCount = \App\Models\Rating::count();
        $reportsCount = \App\Models\Report::count();
        
        if ($ratingsCount === 0 && $reportsCount === 0) {
            $this->command->info('Tidak ada rating atau report, lewati pembuatan moderation log');
            return;
        }
        
        if ($ratingsCount > 0) {
            ModerationLog::factory(20)->forRating()->create();
            $this->command->info('✓ Membuat 20 moderation log untuk rating');
        }
        
        if ($reportsCount > 0) {
            ModerationLog::factory(20)->forReport()->create();
            $this->command->info('✓ Membuat 20 moderation log untuk report');
        }
        
        ModerationLog::factory(50)->create();
        $this->command->info('✓ Membuat 50 moderation log umum');
        
        $this->command->info('ModerationLogSeeder selesai!');
    }
}