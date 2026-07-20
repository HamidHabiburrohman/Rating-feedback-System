<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class FixServiceStructure extends Command
{
    protected $signature = 'fix:services';
    protected $description = 'Fix and complete service layer refactoring';

    protected Filesystem $files;
    protected string $basePath;

    protected array $expectedServices = [
        'Admin/Profile/ProfileService.php',
        'Admin/Dashboard/DashboardService.php',
        'Admin/Employee/EmployeeService.php',
        'Admin/Facility/FacilityService.php',
        'Admin/QRCode/QrCodeService.php',
        'Admin/Rating/RatingCategoryService.php',
        'Admin/Rating/RatingManagementService.php',
        'Admin/Report/ReportCategoryService.php',
        'Admin/Report/ReportManagementService.php',
        'Admin/Setting/SettingService.php',
        'Admin/Unit/UnitDepartmentService.php',
        'Admin/Unit/UnitPhotoService.php',
        'Admin/Unit/UnitService.php',
        'Admin/Unit/UnitTypeService.php',
        'Admin/Shared/BaseAdminService.php',
        'Admin/Conversation/MessageService.php'
    ];

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $this->basePath = app_path('Services');
        
        $this->info('🔍 Checking current service structure...');
        $this->newLine();

        $this->createMissingDirectories();
        $this->findAndMoveOrphanedServices();
        $this->verifyStructure();

        return self::SUCCESS;
    }

    protected function createMissingDirectories(): void
    {
        $this->info('📁 Creating missing directories...');
        
        $directories = [
            'Admin/Profile',
            'Admin/Dashboard',
            'Admin/Employee',
            'Admin/Facility',
            'Admin/QRCode',
            'Admin/Rating',
            'Admin/Report',
            'Admin/Setting',
            'Admin/Unit',
            'Admin/Shared',
            'Admin/Conversation'
    ];

        foreach ($directories as $dir) {
            $path = $this->basePath . '/' . $dir;
            if (!$this->files->isDirectory($path)) {
                $this->files->makeDirectory($path, 0755, true);
                $this->line("  ✓ Created: <info>{$dir}</info>");
            }
        }
        
        $this->newLine();
    }

    protected function findAndMoveOrphanedServices(): void
    {
        $this->info('🔧 Finding and moving orphaned services...');
        
        $allPhpFiles = $this->files->allFiles($this->basePath);
        $movedCount = 0;

        foreach ($allPhpFiles as $file) {
            $filename = $file->getFilename();
            $currentPath = $file->getPathname();
            $relativePath = str_replace($this->basePath . '/', '', $currentPath);

            // Skip if already in correct structure
            if (str_contains($relativePath, '/')) {
                continue;
            }

            // Try to find matching expected service
            foreach ($this->expectedServices as $expectedPath) {
                $expectedFilename = basename($expectedPath);
                
                // Check if filename matches (with or without "Admin" prefix)
                if ($filename === $expectedFilename || 
                    $filename === 'Admin' . $expectedFilename) {
                    
                    $newPath = $this->basePath . '/' . $expectedPath;
                    
                    if (!$this->files->exists($newPath)) {
                        $this->files->move($currentPath, $newPath);
                        $this->updateNamespace($newPath, $expectedPath);
                        $this->line("  ✓ Moved: <comment>{$filename}</comment> → <info>{$expectedPath}</info>");
                        $movedCount++;
                    }
                    break;
                }
            }
        }

        if ($movedCount === 0) {
            $this->line("  ℹ No orphaned services found");
        } else {
            $this->line("  ✓ Moved <info>{$movedCount}</info> service(s)");
        }
        
        $this->newLine();
    }

    protected function updateNamespace(string $filePath, string $expectedPath): void
    {
        $content = $this->files->get($filePath);
        
        // Extract directory structure from path
        $dirPath = dirname($expectedPath);
        $namespaceParts = array_filter(explode('/', $dirPath));
        $namespace = 'App\\Services\\' . implode('\\', $namespaceParts);
        
        // Update namespace
        $content = preg_replace(
            '/namespace\s+[^;]+;/',
            "namespace {$namespace};",
            $content
        );

        // Update class name if needed
        $className = basename($expectedPath, '.php');
        $content = preg_replace(
            '/class\s+\w+/',
            "class {$className}",
            $content
        );

        $this->files->put($filePath, $content);
    }

    protected function verifyStructure(): void
    {
        $this->info('✅ Verifying final structure...');
        
        $missing = [];
        $found = [];

        foreach ($this->expectedServices as $service) {
            $path = $this->basePath . '/' . $service;
            
            if ($this->files->exists($path)) {
                $found[] = $service;
            } else {
                $missing[] = $service;
            }
        }

        $this->newLine();
        $this->line("  ✓ Found: <info>" . count($found) . "</info> services");
        
        if (count($missing) > 0) {
            $this->newLine();
            $this->error("  ✗ Missing " . count($missing) . " services:");
            foreach ($missing as $service) {
                $this->line("    - {$service}");
            }
            $this->newLine();
            $this->warn("  💡 These services might need to be created manually or were deleted.");
        } else {
            $this->newLine();
            $this->info("  🎉 All services are in place!");
        }

        $this->newLine();
        $this->info("Run 'composer dump-autoload' to refresh autoloader.");
    }
}