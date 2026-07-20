<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class FixMissingControllers extends Command
{
    protected $signature = 'app:fix-missing-controllers 
                            {--move : Move existing misplaced controllers to correct namespace}
                            {--dry-run : Show what would be done without modifying files}';

    protected $description = 'Generate missing controller stubs and fix misplaced controllers to match route definitions';

    protected Filesystem $files;

    /**
     * Controllers that need to be created as stubs
     */
    protected array $stubsToCreate = [
        'Admin/Conversation/MessageReadController' => 'Admin\\Conversation',
        'Employee/Conversation/MessageReadController' => 'Employee\\Conversation',
        'Employee/Conversation/MessageAttachmentController' => 'Employee\\Conversation',
        'Student/Conversation/MessageReadController' => 'Student\\Conversation',
        'Student/Conversation/MessageAttachmentController' => 'Student\\Conversation',
    ];

    /**
     * Controllers that need to be moved to correct namespace
     * [old_path => new_path]
     */
    protected array $controllersToMove = [
        'Admin/Setting/ExportController' => 'Admin/Export/ExportController',
        'Admin/Setting/NotificationController' => 'Admin/Notification/NotificationController',
        'Student/QrCodeController' => 'Student/QRCode/QrCodeController',
    ];

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $shouldMove = (bool) $this->option('move');

        $this->info('🔧 Starting Missing Controllers Fix...');
        $this->newLine();

        $createdCount = 0;
        $movedCount = 0;

        // STEP 1: Create missing stub controllers
        $this->info('📝 Creating missing controller stubs...');
        foreach ($this->stubsToCreate as $relativePath => $namespace) {
            $fullPath = app_path("Http/Controllers/{$relativePath}.php");
            $className = basename($relativePath);

            if ($this->files->exists($fullPath)) {
                $this->line("  ⏭️  Skipped (already exists): {$relativePath}");
                continue;
            }

            if ($isDryRun) {
                $this->warn("  [DRY-RUN] Would create: {$relativePath}");
            } else {
                $this->ensureDirectoryExists(dirname($fullPath));
                $content = $this->buildStub($namespace, $className);
                $this->files->put($fullPath, $content);
                $this->info("  ✅ Created: {$relativePath}");
            }

            $createdCount++;
        }

        $this->newLine();

        // STEP 2: Move misplaced controllers (only if --move flag is provided)
        if ($shouldMove) {
            $this->info('📦 Moving misplaced controllers to correct namespace...');
            foreach ($this->controllersToMove as $oldPath => $newPath) {
                $oldFullPath = app_path("Http/Controllers/{$oldPath}.php");
                $newFullPath = app_path("Http/Controllers/{$newPath}.php");

                if (!$this->files->exists($oldFullPath)) {
                    $this->line("  ⏭️  Skipped (source not found): {$oldPath}");
                    continue;
                }

                if ($this->files->exists($newFullPath)) {
                    $this->warn("  ⚠️  Skipped (target already exists): {$newPath}");
                    continue;
                }

                if ($isDryRun) {
                    $this->warn("  [DRY-RUN] Would move: {$oldPath} → {$newPath}");
                } else {
                    $this->ensureDirectoryExists(dirname($newFullPath));
                    
                    $content = $this->files->get($oldFullPath);
                    $content = $this->updateNamespace($content, $oldPath, $newPath);
                    
                    $this->files->put($newFullPath, $content);
                    $this->files->delete($oldFullPath);
                    
                    $this->info("  ✅ Moved: {$oldPath} → {$newPath}");
                }

                $movedCount++;
            }
            $this->newLine();
        } else {
            $this->comment('💡 Tip: Run with --move flag to also relocate misplaced controllers');
            $this->newLine();
        }

        // SUMMARY
        $this->newLine();
        $this->info('📊 Summary:');
        $this->line("   Stubs created : {$createdCount}");
        if ($shouldMove) {
            $this->line("   Controllers moved: {$movedCount}");
        }

        if (!$isDryRun) {
            $this->newLine();
            $this->comment('⚡ Please run "composer dump-autoload" to refresh the class map.');
        }

        return self::SUCCESS;
    }

    protected function ensureDirectoryExists(string $path): void
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0755, true);
        }
    }

    protected function buildStub(string $namespace, string $className): string
    {
        $role = explode('\\', $namespace)[0]; // Admin, Employee, or Student
        
        return <<<PHP
<?php

declare(strict_types=1);

namespace App\\Http\\Controllers\\{$role}\\{$this->getSubNamespace($namespace)};

use App\\Http\\Controllers\\Controller;
use Illuminate\\Http\\Request;

class {$className} extends Controller
{
    public function __construct()
    {
        // TODO: Inject services via constructor property promotion
    }
}

PHP;
    }

    protected function getSubNamespace(string $namespace): string
    {
        $parts = explode('\\', $namespace);
        array_shift($parts); // Remove role (Admin/Employee/Student)
        return implode('\\', $parts);
    }

    protected function updateNamespace(string $content, string $oldPath, string $newPath): string
    {
        $oldNamespaceParts = explode('/', dirname($oldPath));
        $newNamespaceParts = explode('/', dirname($newPath));
        
        $oldNamespace = 'App\\Http\\Controllers\\' . implode('\\', $oldNamespaceParts);
        $newNamespace = 'App\\Http\\Controllers\\' . implode('\\', $newNamespaceParts);
        
        // Handle root-level controllers (like Student/QrCodeController)
        if (dirname($oldPath) === '.') {
            $oldNamespace = 'App\\Http\\Controllers';
        }
        if (dirname($newPath) === '.') {
            $newNamespace = 'App\\Http\\Controllers';
        }

        return str_replace(
            "namespace {$oldNamespace};",
            "namespace {$newNamespace};",
            $content
        );
    }
}