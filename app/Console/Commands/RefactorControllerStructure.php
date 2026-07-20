<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class RefactorControllerStructure extends Command
{
    protected $signature = 'refactor:controllers';
    protected $description = 'Refactor Controllers into a domain-driven directory structure matching the Services layer.';

    protected Filesystem $files;

    protected array $structure = [
        'Admin' => ['Conversation', 'Dashboard', 'Employee', 'Facility', 'Moderation', 'Profile', 'QRCode', 'Rating', 'Report', 'Setting', 'Unit'],
        'Employee' => ['Conversation', 'Dashboard', 'Profile', 'Rating', 'Report', 'Assignment', 'Unit'],
        'Student' => ['Auth', 'Conversation', 'Dashboard', 'Notification', 'Profile', 'Rating', 'Report', 'Unit'],
    ];

    protected array $mappings = [
        'Admin' => [
            'DashboardController' => 'Dashboard',
            'ProfileController' => 'Profile',
            'UnitController' => 'Unit',
            'UnitTypeController' => 'Unit',
            'UnitDepartmentController' => 'Unit',
            'UnitPhotoController' => 'Unit',
            'FacilityController' => 'Facility',
            'EmployeeController' => 'Employee',
            'QrCodeController' => 'QRCode',
            'RatingCategoryController' => 'Rating',
            'RatingController' => 'Rating',
            'ReportCategoryController' => 'Report',
            'ReportController' => 'Report',
            'SettingController' => 'Setting',
            'NotificationController' => 'Setting',
            'ExportController' => 'Setting',
            'ModerationLogController' => 'Moderation',
        ],
        'Employee' => [
            'DashboardController' => 'Dashboard',
            'ProfileController' => 'Profile',
            'RatingController' => 'Rating',
            'ReportController' => 'Report',
            'AssignmentController' => 'Assignment',
            'UnitController' => 'Unit',
        ],
        'Student' => [
            'AuthController' => 'Auth',
            'DashboardController' => 'Dashboard',
            'NotificationController' => 'Notification',
            'ProfileController' => 'Profile',
            'RatingController' => 'Rating',
            'ReportController' => 'Report',
            'UnitController' => 'Unit',
        ]
    ];

    protected array $serviceNamespaceUpdates = [
        'App\\Services\\Admin\\DashboardService' => 'App\\Services\\Admin\\Dashboard\\DashboardService',
        'App\\Services\\Admin\\ProfileService' => 'App\\Services\\Admin\\Profile\\ProfileService',
        'App\\Services\\Admin\\UnitService' => 'App\\Services\\Admin\\Unit\\UnitService',
        'App\\Services\\Admin\\UnitTypeService' => 'App\\Services\\Admin\\Unit\\UnitTypeService',
        'App\\Services\\Admin\\UnitDepartmentService' => 'App\\Services\\Admin\\Unit\\UnitDepartmentService',
        'App\\Services\\Admin\\UnitPhotoService' => 'App\\Services\\Admin\\Unit\\UnitPhotoService',
        'App\\Services\\Admin\\FacilityService' => 'App\\Services\\Admin\\Facility\\FacilityService',
        'App\\Services\\Admin\\EmployeeService' => 'App\\Services\\Admin\\Employee\\EmployeeService',
        'App\\Services\\Admin\\QrCodeService' => 'App\\Services\\Admin\\QRCode\\QrCodeService',
        'App\\Services\\Admin\\RatingCategoryService' => 'App\\Services\\Admin\\Rating\\RatingCategoryService',
        'App\\Services\\Admin\\RatingManagementService' => 'App\\Services\\Admin\\Rating\\RatingManagementService',
        'App\\Services\\Admin\\ReportCategoryService' => 'App\\Services\\Admin\\Report\\ReportCategoryService',
        'App\\Services\\Admin\\ReportManagementService' => 'App\\Services\\Admin\\Report\\ReportManagementService',
        'App\\Services\\Admin\\SettingService' => 'App\\Services\\Admin\\Setting\\SettingService',
        'App\\Services\\Admin\\ModerationLogService' => 'App\\Services\\Admin\\Moderation\\ModerationLogService',
    ];

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $this->info('Starting Controller Structure Refactoring...');
        $this->newLine();

        $this->createDirectories();
        $this->moveAndRefactorControllers();
        $this->generateConversationStubs();
        $this->updateGlobalImports();

        $this->newLine();
        $this->info('✔ Controller refactoring completed successfully.');
        $this->comment('Please run "composer dump-autoload" and "php artisan route:list" to verify.');

        return self::SUCCESS;
    }

    protected function createDirectories(): void
    {
        $basePath = app_path('Http/Controllers');
        foreach ($this->structure as $role => $domains) {
            foreach ($domains as $domain) {
                $path = "{$basePath}/{$role}/{$domain}";
                if (!$this->files->isDirectory($path)) {
                    $this->files->makeDirectory($path, 0755, true);
                }
            }
        }
        $this->info('✔ Directories verified/created.');
    }

    protected function moveAndRefactorControllers(): void
    {
        $basePath = app_path('Http/Controllers');
        $movedCount = 0;

        foreach ($this->mappings as $role => $controllers) {
            foreach ($controllers as $controller => $domain) {
                $oldPath = "{$basePath}/{$role}/{$controller}.php";
                $newPath = "{$basePath}/{$role}/{$domain}/{$controller}.php";

                if ($this->files->exists($oldPath) && !$this->files->exists($newPath)) {
                    $content = $this->files->get($oldPath);
                    
                    // 1. Update Namespace
                    $oldNamespace = "namespace App\\Http\\Controllers\\{$role};";
                    $newNamespace = "namespace App\\Http\\Controllers\\{$role}\\{$domain};";
                    $content = str_replace($oldNamespace, $newNamespace, $content);

                    // 2. Update Service Dependency Injections
                    foreach ($this->serviceNamespaceUpdates as $oldService => $newService) {
                        $content = str_replace("use {$oldService};", "use {$newService};", $content);
                    }

                    $this->files->put($newPath, $content);
                    $this->files->delete($oldPath);
                    $movedCount++;
                }
            }
        }
        $this->info("✔ Moved and refactored {$movedCount} controllers.");
    }

    protected function generateConversationStubs(): void
    {
        $basePath = app_path('Http/Controllers');
        $stubsCreated = 0;

        $conversationControllers = [
            'Admin' => ['ConversationController', 'MessageController', 'MessageAttachmentController', 'ConversationParticipantController'],
            'Employee' => ['ConversationController', 'MessageController'],
            'Student' => ['ConversationController', 'MessageController'],
        ];

        foreach ($conversationControllers as $role => $controllers) {
            foreach ($controllers as $controller) {
                $path = "{$basePath}/{$role}/Conversation/{$controller}.php";
                if (!$this->files->exists($path)) {
                    $namespace = "App\\Http\\Controllers\\{$role}\\Conversation";
                    $content = $this->getStubContent($namespace, $controller, $role);
                    $this->files->put($path, $content);
                    $stubsCreated++;
                }
            }
        }
        $this->info("✔ Generated {$stubsCreated} Conversation controller stubs.");
    }

    protected function getStubContent(string $namespace, string $className, string $role): string
    {
        $serviceUse = "";
        if ($role === 'Admin') {
            $serviceUse = "use App\\Services\\Admin\\Conversation\\ConversationService;\nuse App\\Services\\Admin\\Conversation\\MessageService;";
        } elseif ($role === 'Employee') {
            $serviceUse = "use App\\Services\\Employee\\Conversation\\ConversationService;\nuse App\\Services\\Employee\\Conversation\\MessageService;";
        } elseif ($role === 'Student') {
            $serviceUse = "use App\\Services\\Student\\Conversation\\ConversationService;\nuse App\\Services\\Student\\Conversation\\MessageService;";
        }

        return <<<PHP
<?php

declare(strict_types=1);

namespace {$namespace};

use App\Http\Controllers\Controller;
{$serviceUse}

class {$className} extends Controller
{
    // TODO: Implement methods and inject services via constructor property promotion
}
PHP;
    }

    protected function updateGlobalImports(): void
    {
        $directories = array_filter([
            base_path('app'),
            base_path('routes'),
            base_path('tests'),
            base_path('config'),
        ], fn($dir) => is_dir($dir));

        if (empty($directories)) return;

        $finder = new Finder();
        $finder->files()->in($directories)->name('*.php');
        $updatedFiles = 0;

        foreach ($finder as $file) {
            $path = $file->getRealPath();
            $content = $this->files->get($path);
            $original = $content;

            foreach ($this->mappings as $role => $controllers) {
                foreach ($controllers as $controller => $domain) {
                    $oldNamespace = "App\\Http\\Controllers\\{$role}";
                    $newNamespace = "App\\Http\\Controllers\\{$role}\\{$domain}";

                    // Update use statements
                    $content = str_replace(
                        "use {$oldNamespace}\\{$controller};",
                        "use {$newNamespace}\\{$controller};",
                        $content
                    );

                    // Update inline FQCN (e.g. in routes or config)
                    $content = str_replace(
                        "{$oldNamespace}\\{$controller}::class",
                        "{$newNamespace}\\{$controller}::class",
                        $content
                    );
                }
            }

            if ($content !== $original) {
                $this->files->put($path, $content);
                $updatedFiles++;
            }
        }
        $this->info("✔ Updated global imports in {$updatedFiles} files (routes, tests, policies, etc.).");
    }
}