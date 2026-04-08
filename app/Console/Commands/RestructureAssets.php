<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RestructureAssets extends Command
{
    protected $signature = 'assets:restructure {--clean}';
    protected $description = 'Restructure public/assets folder';

    public function handle()
    {
        $base = public_path('assets');

        $this->info('🚀 Starting assets restructuring...');

        // === CREATE NEW STRUCTURE ===
        $folders = [
            'admin/css',
            'admin/js',
            'student/css',
            'student/js',
            'landing/css',
            'landing/js',
            'shared/css',
            'shared/js',
            'shared/components',
            'vendor',
        ];

        foreach ($folders as $folder) {
            File::ensureDirectoryExists("$base/$folder");
        }

        // === MOVE ADMIN FILES ===
        $this->move("$base/admin/css", "$base/admin/css");
        $this->move("$base/admin/js", "$base/admin/js");

        // === MOVE COMPONENTS → SHARED ===
        if (File::exists("$base/components")) {
            File::copyDirectory("$base/components", "$base/shared/components");
        }

        // === GLOBAL CSS → SHARED ===
        if (File::exists("$base/css")) {
            File::copyDirectory("$base/css", "$base/shared/css");
        }

        // === VISITOR → STUDENT ===
        if (File::exists("$base/js/visitor.js")) {
            File::move("$base/js/visitor.js", "$base/student/js/visitor.js");
        }

        // === LANDING ===
        if (File::exists("$base/landing")) {
            File::copyDirectory("$base/landing", "$base/landing");
        }

        // === LIBS → VENDOR (ONLY DIST) ===
        if (File::exists("$base/libs")) {
            $libs = File::directories("$base/libs");

            foreach ($libs as $lib) {
                $name = basename($lib);

                if (File::exists("$lib/dist")) {
                    File::copyDirectory("$lib/dist", "$base/vendor/$name");
                }
            }
        }

        // === IMAGES ===
        // tetap

        // === CLEAN UNUSED FILES ===
        if ($this->option('clean')) {
            $this->warn('🧹 Cleaning unused files...');

            File::deleteDirectory("$base/libs");
            File::deleteDirectory("$base/components");
            File::deleteDirectory("$base/css");
            File::deleteDirectory("$base/js");
        }

        $this->info('✅ Restructure completed!');
    }

    private function move($from, $to)
    {
        if (File::exists($from)) {
            File::copyDirectory($from, $to);
        }
    }
}