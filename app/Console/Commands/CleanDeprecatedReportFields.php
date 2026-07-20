<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\Finder;

class CleanDeprecatedReportFields extends Command
{
    protected $signature = 'app:clean-report-fields 
                            {--dry-run : Only show what would be changed without modifying files}
                            {--path=app : Base path to scan}';

    protected $description = 'Automatically remove deprecated report fields (admin_response, assigned_to_employee_id, replied_at) from FormRequests and Services';

    protected array $deprecatedFields = [
        'replied_at'
    ];

    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $basePath = base_path($this->option('path'));

        if (!File::isDirectory($basePath)) {
            $this->error("Path not found: {$basePath}");
            return self::FAILURE;
        }

        $this->info('Scanning for deprecated report fields...');
        $this->newLine();

        $finder = new Finder();
        $finder->files()->in($basePath)->name('*.php');

        $modifiedCount = 0;
        $scannedCount = 0;

        foreach ($finder as $file) {
            $scannedCount++;
            $path = $file->getRealPath();
            $originalContent = File::get($path);
            $content = $originalContent;

            foreach ($this->deprecatedFields as $field) {
                $content = $this->removeFieldReferences($content, $field);
            }

            if ($content !== $originalContent) {
                $modifiedCount++;
                $relativePath = str_replace(base_path() . '/', '', $path);

                if ($isDryRun) {
                    $this->warn("[DRY-RUN] Would modify: {$relativePath}");
                } else {
                    File::put($path, $content);
                    $this->info("✓ Cleaned: {$relativePath}");
                }
            }
        }

        $this->newLine();
        $this->info("Scan complete. Files scanned: {$scannedCount}");

        if ($isDryRun) {
            $this->warn("Files that would be modified: {$modifiedCount}");
            $this->newLine();
            $this->comment('Run without --dry-run to apply changes.');
        } else {
            $this->info("Files modified: {$modifiedCount}");
        }

        return self::SUCCESS;
    }

    protected function removeFieldReferences(string $content, string $field): string
    {
        $lines = explode("\n", $content);
        $cleanedLines = [];
        $skipNextMessage = false;

        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];
            $trimmed = trim($line);

            if ($this->isDeprecatedFieldLine($trimmed, $field)) {
                $skipNextMessage = true;
                continue;
            }

            if ($skipNextMessage && $this->isCustomMessageLine($trimmed, $field)) {
                continue;
            }

            $skipNextMessage = false;
            $cleanedLines[] = $line;
        }

        $result = implode("\n", $cleanedLines);
        $result = $this->cleanEmptyArrays($result);
        $result = $this->removeTrailingCommas($result);

        return $result;
    }

    protected function isDeprecatedFieldLine(string $line, string $field): bool
    {
        $patterns = [
            "/['\"]" . preg_quote($field, '/') . "['\"]\s*=>/",
            "/['\"]" . preg_quote($field, '/') . "['\"]\s*\|\s*/",
            "/['\"]" . preg_quote($field, '/') . "['\"]\s*,/",
            "/['\"]" . preg_quote($field, '/') . "['\"]\s*\)/",
            "/->\s*where\s*\(\s*['\"]" . preg_quote($field, '/') . "['\"]/",
            "/->\s*update\s*\(\s*\[\s*['\"]" . preg_quote($field, '/') . "['\"]/",
            "/['\"]" . preg_quote($field, '/') . "['\"]\s*=>\s*\$/"
    ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $line)) {
                return true;
            }
        }

        return false;
    }

    protected function isCustomMessageLine(string $line, string $field): bool
    {
        return (bool) preg_match(
            "/['\"]" . preg_quote($field, '/') . "\.(max|required|min|string|nullable|exists)['\"]\s*=>/",
            $line
        );
    }

    protected function cleanEmptyArrays(string $content): string
    {
        $content = preg_replace(
            "/'rules'\s*:\s*array\s*\(\s*\)\s*,/",
            "'rules' => [],",
            $content
        );

        return $content;
    }

    protected function removeTrailingCommas(string $content): string
    {
        $content = preg_replace(
            "/,\s*\n\s*\];/",
            "\n    ];",
            $content
        );

        $content = preg_replace(
            "/,\s*\n\s*\)/",
            "\n    )",
            $content
        );

        return $content;
    }
}