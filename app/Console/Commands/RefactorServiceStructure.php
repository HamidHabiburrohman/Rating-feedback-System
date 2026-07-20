<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\Finder;

class RefactorServiceStructure extends Command
{
    protected $signature = 'refactor:services';
    protected $description = 'Refactor the Service layer into a domain-driven architecture.';

    protected string $basePath;

    protected array $directories = [
        'Admin/Conversation',
        'Admin/Dashboard',
        'Admin/Employee',
        'Admin/Facility',
        'Admin/Moderation',
        'Admin/Profile',
        'Admin/QRCode',
        'Admin/Rating',
        'Admin/Report',
        'Admin/Setting',
        'Admin/Unit',
        'Admin/Shared',
        'Employee/Assignment',
        'Employee/Dashboard',
        'Employee/Profile',
        'Employee/Rating',
        'Employee/Report',
        'Employee/Shared',
        'Export/Contracts',
        'Export/Exports',
        'Export/Manager',
        'Export/Queue',
        'Shared/File',
        'Shared/GPS',
        'Shared/Notification',
        'Shared/Support',
        'Student/Auth',
        'Student/Dashboard',
        'Student/Notification',
        'Student/Profile',
        'Student/QRValidation',
        'Student/Rating',
        'Student/Report',
        'Student/Unit',
        'Student/Shared'
    ];

    protected array $fileMappings = [
        'ProfileService.php' => ['path' => 'Admin/Profile/ProfileService.php', 'old_class' => 'ProfileService', 'new_class' => 'ProfileService', 'old_ns' => 'App\Services\Admin', 'new_ns' => 'App\Services\Admin\Profile'],
        'DashboardService.php' => ['path' => 'Admin/Dashboard/DashboardService.php', 'old_class' => 'DashboardService', 'new_class' => 'DashboardService', 'old_ns' => 'App\Services\Admin', 'new_ns' => 'App\Services\Admin\Dashboard'],
        'EmployeeService.php' => ['path' => 'Admin/Employee/EmployeeService.php', 'old_class' => 'EmployeeService', 'new_class' => 'EmployeeService', 'old_ns' => 'App\Services\Admin', 'new_ns' => 'App\Services\Admin\Employee'],
        'FacilityService.php' => ['path' => 'Admin/Facility/FacilityService.php', 'old_class' => 'FacilityService', 'new_class' => 'FacilityService', 'old_ns' => 'App\Services\Admin', 'new_ns' => 'App\Services\Admin\Facility'],
        'QrCodeService.php' => ['path' => 'Admin/QRCode/QrCodeService.php', 'old_class' => 'QrCodeService', 'new_class' => 'QrCodeService', 'old_ns' => 'App\Services\Admin', 'new_ns' => 'App\Services\Admin\QRCode'],
        'RatingCategoryService.php' => ['path' => 'Admin/Rating/RatingCategoryService.php', 'old_class' => 'RatingCategoryService', 'new_class' => 'RatingCategoryService', 'old_ns' => 'App\Services\Admin', 'new_ns' => 'App\Services\Admin\Rating'],
        'RatingManagementService.php' => ['path' => 'Admin/Rating/RatingManagementService.php', 'old_class' => 'RatingManagementService', 'new_class' => 'RatingManagementService', 'old_ns' => 'App\Services\Admin', 'new_ns' => 'App\Services\Admin\Rating'],
        'ReportCategoryService.php' => ['path' => 'Admin/Report/ReportCategoryService.php', 'old_class' => 'ReportCategoryService', 'new_class' => 'ReportCategoryService', 'old_ns' => 'App\Services\Admin', 'new_ns' => 'App\Services\Admin\Report'],
        'ReportManagementService.php' => ['path' => 'Admin/Report/ReportManagementService.php', 'old_class' => 'ReportManagementService', 'new_class' => 'ReportManagementService', 'old_ns' => 'App\Services\Admin', 'new_ns' => 'App\Services\Admin\Report'],
        'SettingService.php' => ['path' => 'Admin/Setting/SettingService.php', 'old_class' => 'SettingService', 'new_class' => 'SettingService', 'old_ns' => 'App\Services\Admin', 'new_ns' => 'App\Services\Admin\Setting'],
        'UnitDepartmentService.php' => ['path' => 'Admin/Unit/UnitDepartmentService.php', 'old_class' => 'UnitDepartmentService', 'new_class' => 'UnitDepartmentService', 'old_ns' => 'App\Services\Admin', 'new_ns' => 'App\Services\Admin\Unit'],
        'UnitPhotoService.php' => ['path' => 'Admin/Unit/UnitPhotoService.php', 'old_class' => 'UnitPhotoService', 'new_class' => 'UnitPhotoService', 'old_ns' => 'App\Services\Admin', 'new_ns' => 'App\Services\Admin\Unit'],
        'UnitService.php' => ['path' => 'Admin/Unit/UnitService.php', 'old_class' => 'UnitService', 'new_class' => 'UnitService', 'old_ns' => 'App\Services\Admin', 'new_ns' => 'App\Services\Admin\Unit'],
        'UnitTypeService.php' => ['path' => 'Admin/Unit/UnitTypeService.php', 'old_class' => 'UnitTypeService', 'new_class' => 'UnitTypeService', 'old_ns' => 'App\Services\Admin', 'new_ns' => 'App\Services\Admin\Unit'],
        'BaseAdminService.php' => ['path' => 'Admin/Shared/BaseAdminService.php', 'old_class' => 'BaseAdminService', 'new_class' => 'BaseAdminService', 'old_ns' => 'App\Services\Admin', 'new_ns' => 'App\Services\Admin\Shared'],
        'MessageService.php' => ['path' => 'Admin/Conversation/MessageService.php', 'old_class' => 'MessageService', 'new_class' => 'MessageService', 'old_ns' => 'App\Services\Admin', 'new_ns' => 'App\Services\Admin\Conversation']
    ];

    protected array $newConversationServices = [
        'ConversationService',
        'ConversationParticipantService',
        'MessageAttachmentService',
        'MessageReadService'
    ];

    public function handle(): int
    {
        $this->info('Starting Service Layer Refactoring...');
        $this->basePath = app_path('Services');

        $this->createDirectories();
        $this->moveAndRefactorServices();
        $this->generateConversationServices();
        $this->updateProjectImports();
        $this->verifyRefactoring();

        $this->newLine();
        $this->info('✔ Refactor completed successfully.');

        return self::SUCCESS;
    }

    protected function createDirectories(): void
    {
        foreach ($this->directories as $dir) {
            $path = $this->basePath . '/' . $dir;
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true);
                $this->line("Created directory: <info>{$dir}</info>");
            }
        }
    }

    protected function moveAndRefactorServices(): void
    {
        foreach ($this->fileMappings as $oldFile => $config) {
            $oldPath = $this->basePath . '/' . $oldFile;
            $newPath = $this->basePath . '/' . $config['path'];

            if (File::exists($oldPath) && !File::exists($newPath)) {
                File::move($oldPath, $newPath);

                $content = File::get($newPath);
                $content = str_replace(
                    "namespace {$config['old_ns']};",
                    "namespace {$config['new_ns']};",
                    $content
                );
                $content = str_replace(
                    "class {$config['old_class']}",
                    "class {$config['new_class']}",
                    $content
                );

                File::put($newPath, $content);
                $this->line("Moved and refactored: <info>{$oldFile}</info> -> <info>{$config['path']}</info>");
            }
        }
    }

    protected function generateConversationServices(): void
    {
        $namespace = 'App\Services\Admin\Conversation';
        $dir = $this->basePath . '/Admin/Conversation';

        foreach ($this->newConversationServices as $service) {
            $path = $dir . '/' . $service . '.php';
            if (!File::exists($path)) {
                $content = $this->getServiceStub($namespace, $service);
                File::put($path, $content);
                $this->line("Generated new service: <info>Admin/Conversation/{$service}.php</info>");
            }
        }
    }

    protected function getServiceStub(string $namespace, string $className): string
    {
        return <<<PHP
<?php

declare(strict_types=1);

namespace {$namespace};

use Illuminate\Support\Facades\DB;

final class {$className}
{
    public function __construct(
        private readonly string \$placeholder = ''
    ) {}
}
PHP;
    }

    protected function updateProjectImports(): void
    {
        $directories = array_filter([
            base_path('app'),
            base_path('routes'),
            base_path('config'),
            base_path('database'),
            base_path('tests'),
        ], fn(string $dir) => is_dir($dir));

        if (empty($directories)) {
            return;
        }

        $finder = new Finder();
        $finder->files()->in($directories)->name('*.php');

        $updatedFiles = 0;

        foreach ($finder as $file) {
            $path = $file->getRealPath();
            $content = File::get($path);
            $originalContent = $content;

            foreach ($this->fileMappings as $config) {
                $oldUse = "use {$config['old_ns']}\\{$config['old_class']};";
                $newUse = "use {$config['new_ns']}\\{$config['new_class']};";
                $content = str_replace($oldUse, $newUse, $content);

                $oldFqcn = "\\{$config['old_ns']}\\{$config['old_class']}";
                $newFqcn = "\\{$config['new_ns']}\\{$config['new_class']}";
                $content = str_replace($oldFqcn, $newFqcn, $content);

                if ($config['old_class'] !== $config['new_class']) {
                    $content = preg_replace("/\b{$config['old_class']}\b/", $config['new_class'], $content);
                }
            }

            if ($content !== $originalContent) {
                File::put($path, $content);
                $updatedFiles++;
            }
        }

        $this->line("Updated imports in <info>{$updatedFiles}</info> files.");
    }

    protected function verifyRefactoring(): void
    {
        $this->newLine();
        $this->info('Verifying refactoring...');

        $errors = 0;
        foreach ($this->fileMappings as $config) {
            $newPath = $this->basePath . '/' . $config['path'];
            if (!File::exists($newPath)) {
                $this->error("Missing file: {$config['path']}");
                $errors++;
            }
        }

        if ($errors === 0) {
            $this->info('✔ All services successfully moved and verified.');
        } else {
            $this->error("✖ Verification failed with {$errors} errors.");
        }
    }
}
