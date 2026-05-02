<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class RemoveComments extends Command
{
    protected $signature = 'remove:comments {--dry-run : Preview changes without actually modifying files}';
    protected $description = 'Remove all comments from PHP files except routes/web.php';

    protected $excludedFiles = [];
    protected $totalFilesProcessed = 0;
    protected $totalCommentsRemoved = 0;

    public function __construct()
    {
        parent::__construct();
        $this->excludedFiles = [
            base_path('routes/web.php'),
        ];
    }

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('RUNNING IN DRY-RUN MODE - No files will be modified');
            $this->info('==================================================');
        }

        // Process PHP files
        $this->processDirectory(app_path());
        $this->processDirectory(base_path('routes'));
        $this->processDirectory(base_path('config'));
        $this->processDirectory(base_path('database'));
        $this->processDirectory(base_path('resources/views'));
        
        // Optional: Uncomment kalo mau include directory lainnya
        // $this->processDirectory(base_path('tests'));
        // $this->processDirectory(base_path('bootstrap'));

        $this->newLine();
        $this->info("Summary:");
        $this->info("- Total files processed: {$this->totalFilesProcessed}");
        $this->info("- Total comments removed: {$this->totalCommentsRemoved}");
        
        if ($isDryRun) {
            $this->warn("This was a dry run. Run without --dry-run to actually modify files.");
        }
    }

    protected function processDirectory($directory)
    {
        if (!File::isDirectory($directory)) {
            return;
        }

        $files = File::allFiles($directory);
        
        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $filePath = $file->getPathname();
            
            // Skip excluded files
            if (in_array($filePath, $this->excludedFiles)) {
                $this->line("<fg=yellow>Skipping: {$this->getRelativePath($filePath)}</>");
                continue;
            }

            $this->removeCommentsFromFile($filePath);
        }
    }

    protected function removeCommentsFromFile($filePath)
    {
        $content = File::get($filePath);
        $originalContent = $content;
        
        // Remove multi-line comments /* ... */
        $content = $this->removeMultiLineComments($content);
        
        // Remove single-line comments // ...
        $content = $this->removeSingleLineComments($content);
        
        // Remove hash comments # ... (less common in Laravel)
        $content = $this->removeHashComments($content);
        
        // Remove PHPDoc blocks (optional - careful with this!)
        // Uncomment if you want to remove docblocks too
        // $content = $this->removeDocBlocks($content);
        
        // Clean up multiple blank lines
        $content = preg_replace("/\n\s*\n\s*\n/", "\n\n", $content);
        
        if ($content !== $originalContent) {
            $commentsCount = $this->countRemovedComments($originalContent, $content);
            $this->totalCommentsRemoved += $commentsCount;
            $this->totalFilesProcessed++;
            
            $relativePath = $this->getRelativePath($filePath);
            
            if (!$this->option('dry-run')) {
                File::put($filePath, $content);
                $this->info("✓ Cleaned: {$relativePath} ({$commentsCount} comments)");
            } else {
                $this->line("<fg=green>Would clean: {$relativePath} ({$commentsCount} comments)</>");
            }
        }
    }

    protected function removeMultiLineComments($content)
    {
        // Remove /* ... */ comments
        return preg_replace('/\/\*[\s\S]*?\*\//', '', $content);
    }

    protected function removeSingleLineComments($content)
    {
        $lines = explode("\n", $content);
        $result = [];
        
        foreach ($lines as $line) {
            // Check if line is inside a string
            $trimmed = trim($line);
            
            // Preserve lines that start with // but are inside strings
            if (preg_match('/^\s*\/\//', $line) && !$this->isInsideString($line)) {
                // Check if it's a commented code line (starts with // but contains code)
                if (preg_match('/^\s*\/\/\s*(use|namespace|return|if|for|foreach|while|function|class|public|private|protected|static|const)/', $line)) {
                    // This might be commented-out code, keep it but uncomment if needed
                    // For safety, we'll remove it anyway since you want all comments gone
                    continue;
                }
                // Remove pure comment lines
                continue;
            }
            
            // Remove inline comments (// ... at end of line)
            // But be careful with URLs and strings
            $cleanedLine = $this->removeInlineComment($line);
            $result[] = $cleanedLine;
        }
        
        return implode("\n", $result);
    }

    protected function removeInlineComment($line)
    {
        // Don't process if line contains http:// or https://
        if (preg_match('/https?:\/\//', $line)) {
            return $line;
        }
        
        // Simple inline comment removal (basic approach)
        // This regex tries to find // that's not inside a string
        $pattern = '/(?<!http:|https:)(?<!["\'])\/\/.*$/m';
        return preg_replace($pattern, '', $line);
    }

    protected function removeHashComments($content)
    {
        $lines = explode("\n", $content);
        $result = [];
        
        foreach ($lines as $line) {
            if (preg_match('/^\s*#/', $line) && !$this->isInsideString($line)) {
                continue;
            }
            $result[] = $line;
        }
        
        return implode("\n", $result);
    }

    protected function removeDocBlocks($content)
    {
        // Remove PHPDoc blocks
        return preg_replace('/\/\*\*[\s\S]*?\*\//', '', $content);
    }

    protected function isInsideString($line)
    {
        // Simple check - if line contains unbalanced quotes, might be inside string
        $singleQuotes = substr_count($line, "'") - substr_count($line, "\\'");
        $doubleQuotes = substr_count($line, '"') - substr_count($line, '\\"');
        
        return ($singleQuotes % 2 !== 0) || ($doubleQuotes % 2 !== 0);
    }

    protected function countRemovedComments($original, $cleaned)
    {
        $originalLines = count(array_filter(explode("\n", $original), function($line) {
            return preg_match('/^\s*(\/\/|#|\/\*|\*)/', trim($line));
        }));
        
        $cleanedLines = count(array_filter(explode("\n", $cleaned), function($line) {
            return preg_match('/^\s*(\/\/|#|\/\*|\*)/', trim($line));
        }));
        
        return $originalLines - $cleanedLines;
    }

    protected function getRelativePath($fullPath)
    {
        return str_replace(base_path() . '/', '', $fullPath);
    }
}