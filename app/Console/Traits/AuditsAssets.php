<?php

namespace App\Console\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait AuditsAssets
{
    protected function getReferencedAssets(): array
    {
        $references = [];
        
        $bladeFiles = File::allFiles(resource_path('views'));
        foreach ($bladeFiles as $file) {
            if ($file->getExtension() === 'php') {
                $content = $file->getContents();
                preg_match_all('/[\'"]\/assets\/([^\'"\s\)]+)[\'"]|asset\([\'"]assets\/([^\'"\s\)]+)[\'"]\)|url\([\'"]?\/assets\/([^\'"\s\)]+)[\'"]?\)/', $content, $matches);
                $refs = array_merge($matches[1], $matches[2], $matches[3]);
                $references = array_merge($references, array_filter($refs));
            }
        }

        $assetFiles = File::allFiles(public_path('assets'));
        foreach ($assetFiles as $file) {
            if (in_array($file->getExtension(), ['js', 'css'])) {
                $content = $file->getContents();
                preg_match_all('/[\'"]\/assets\/([^\'"\s\)]+)[\'"]|url\([\'"]?\/assets\/([^\'"\s\)]+)[\'"]?\)/', $content, $matches);
                $refs = array_merge($matches[1], $matches[2]);
                $references = array_merge($references, array_filter($refs));
            }
        }

        return array_unique($references);
    }

    protected function getExistingAssets(): array
    {
        $files = File::allFiles(public_path('assets'));
        $paths = [];
        foreach ($files as $file) {
            $paths[] = Str::replaceFirst(public_path('assets/'), '', $file->getPathname());
        }
        return $paths;
    }

    protected function categorizeAssets(): array
    {
        $referenced = $this->getReferencedAssets();
        $existing = $this->getExistingAssets();

        $used = array_intersect($existing, $referenced);
        $unused = array_diff($existing, $referenced);

        $legacy = [];
        $orphans = [];
        $duplicates = [];

        $legacyPatterns = ['libs/', 'scss/', 'vendor/spike', 'vendor/bootstrap/scss', 'vendor/jquery/src', 'vendor/simplebar/src'];
        foreach ($unused as $path) {
            foreach ($legacyPatterns as $pattern) {
                if (Str::contains($path, $pattern)) {
                    $legacy[] = $path;
                    break;
                }
            }
        }

        $rootDirs = ['css', 'js', 'images'];
        foreach ($rootDirs as $dir) {
            if (File::isDirectory(public_path($dir))) {
                $files = File::allFiles(public_path($dir));
                foreach ($files as $file) {
                    $orphans[] = $dir . '/' . Str::replaceFirst(public_path($dir) . '/', '', $file->getPathname());
                }
            }
        }

        $filenames = [];
        foreach ($existing as $path) {
            $name = basename($path);
            if (!isset($filenames[$name])) {
                $filenames[$name] = [];
            }
            $filenames[$name][] = $path;
        }
        foreach ($filenames as $name => $paths) {
            if (count($paths) > 1) {
                $duplicates[$name] = $paths;
            }
        }

        return [
            'used' => array_values($used),
            'unused' => array_values($unused),
            'legacy' => array_values($legacy),
            'orphans' => array_values($orphans),
            'duplicates' => $duplicates,
        ];
    }
}