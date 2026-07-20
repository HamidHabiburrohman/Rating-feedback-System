<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class RefactorDatabaseStructure extends Command
{
    protected $signature = 'refactor:database-structure';
    
    protected $description = 'Automatically refactor Factories and Seeders into domain-based directory structures.';

    protected Filesystem $files;

    protected array $factoryMapping = [
        'AdminFactory' => 'Admin',
        'ConversationFactory' => 'Conversation',
        'MessageFactory' => 'Conversation',
        'MessageAttachmentFactory' => 'Conversation',
        'EmployeeFactory' => 'Employee',
        'EmployeePositionFactory' => 'Employee',
        'EmployeeUnitAssignmentFactory' => 'Employee',
        'ExportFactory' => 'Export',
        'FacilityFactory' => 'Facility',
        'ModerationLogFactory' => 'Moderation',
        'NotificationFactory' => 'Notification',
        'QrCodeFactory' => 'QRCode',
        'RatingFactory' => 'Rating',
        'RatingAttachmentFactory' => 'Rating',
        'RatingCategoryFactory' => 'Rating',
        'RatingReplyFactory' => 'Rating',
        'RatingScoreFactory' => 'Rating',
        'ReportFactory' => 'Report',
        'ReportAttachmentFactory' => 'Report',
        'ReportCategoryFactory' => 'Report',
        'ReportReplyFactory' => 'Report',
        'ReportStatusHistoryFactory' => 'Report',
        'SettingFactory' => 'Setting',
        'StudentFactory' => 'Student',
        'UnitFactory' => 'Unit',
        'UnitDepartmentFactory' => 'Unit',
        'UnitFacilityFactory' => 'Unit',
        'UnitPhotoFactory' => 'Unit',
        'UnitTypeFactory' => 'Unit',
        'UnitVisitFactory' => 'Unit'
    ];

    protected array $seederMapping = [
        'AdminSeeder' => 'Admin',
        'ConversationSeeder' => 'Conversation',
        'MessageSeeder' => 'Conversation',
        'EmployeeSeeder' => 'Employee',
        'EmployeePositionSeeder' => 'Employee',
        'EmployeeUnitAssignmentSeeder' => 'Employee',
        'ExportSeeder' => 'Export',
        'FacilitySeeder' => 'Facility',
        'ModerationLogSeeder' => 'Moderation',
        'NotificationSeeder' => 'Notification',
        'QrCodeSeeder' => 'QRCode',
        'RatingSeeder' => 'Rating',
        'RatingAttachmentSeeder' => 'Rating',
        'RatingCategorySeeder' => 'Rating',
        'RatingReplySeeder' => 'Rating',
        'RatingScoreSeeder' => 'Rating',
        'ReportSeeder' => 'Report',
        'ReportAttachmentSeeder' => 'Report',
        'ReportCategorySeeder' => 'Report',
        'ReportReplySeeder' => 'Report',
        'ReportStatusHistorySeeder' => 'Report',
        'SettingSeeder' => 'Setting',
        'StudentSeeder' => 'Student',
        'UnitSeeder' => 'Unit',
        'UnitDepartmentSeeder' => 'Unit',
        'UnitFacilitySeeder' => 'Unit',
        'UnitPhotoSeeder' => 'Unit',
        'UnitTypeSeeder' => 'Unit',
        'UnitVisitSeeder' => 'Unit'
    ];

    protected array $domains = [
        'Admin', 'Conversation', 'Employee', 'Export', 'Facility', 
        'Moderation', 'Notification', 'QRCode', 'Rating', 'Report', 
        'Setting', 'Student', 'Unit'
    ];

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $this->info('Starting database structure refactoring...');
        $this->newLine();

        $this->createDirectories();
        $this->moveAndRefactorFiles('factories', $this->factoryMapping, 'Database\\Factories');
        $this->moveAndRefactorFiles('seeders', $this->seederMapping, 'Database\\Seeders');
        $this->updateProjectReferences();

        $this->newLine();
        $this->info('✔ Directories created');
        $this->info('✔ Factories moved');
        $this->info('✔ Seeders moved');
        $this->info('✔ Namespaces updated');
        $this->info('✔ Imports updated');
        $this->info('✔ DatabaseSeeder updated');
        $this->info('✔ Model factories updated');
        $this->info('✔ Refactor completed successfully');
        
        $this->newLine();
        $this->comment('Please run "composer dump-autoload" to update the classmap autoloader.');

        return self::SUCCESS;
    }

    protected function createDirectories(): void
    {
        $basePath = database_path();
        
        foreach ($this->domains as $domain) {
            $factoryDir = "{$basePath}/factories/{$domain}";
            $seederDir = "{$basePath}/seeders/{$domain}";

            if (!$this->files->isDirectory($factoryDir)) {
                $this->files->makeDirectory($factoryDir, 0755, true);
            }
            
            if (!$this->files->isDirectory($seederDir)) {
                $this->files->makeDirectory($seederDir, 0755, true);
            }
        }
    }

    protected function moveAndRefactorFiles(string $type, array $mapping, string $baseNamespace): void
    {
        $basePath = database_path($type);
        
        foreach ($mapping as $fileName => $domain) {
            $oldPath = "{$basePath}/{$fileName}.php";
            $newPath = "{$basePath}/{$domain}/{$fileName}.php";

            if ($this->files->exists($oldPath)) {
                $this->files->move($oldPath, $newPath);
                
                $content = $this->files->get($newPath);
                
                $oldNamespace = "namespace {$baseNamespace};";
                $newNamespace = "namespace {$baseNamespace}\\{$domain};";
                $content = str_replace($oldNamespace, $newNamespace, $content);
                
                foreach ($mapping as $otherFile => $otherDomain) {
                    if ($otherFile !== $fileName) {
                        $oldUse = "use {$baseNamespace}\\{$otherFile};";
                        $newUse = "use {$baseNamespace}\\{$otherDomain}\\{$otherFile};";
                        $content = str_replace($oldUse, $newUse, $content);
                        
                        $oldStatic = "{$baseNamespace}\\{$otherFile}";
                        $newStatic = "{$baseNamespace}\\{$otherDomain}\\{$otherFile}";
                        $content = str_replace($oldStatic, $newStatic, $content);
                    }
                }
                
                $this->files->put($newPath, $content);
            }
        }
    }

    protected function updateProjectReferences(): void
    {
        $directories = array_filter([
            app_path(),
            database_path(),
            base_path('tests'),
            base_path('routes'),
        ], fn($dir) => is_dir($dir));

        if (empty($directories)) {
            return;
        }

        $finder = new Finder();
        $finder->files()->in($directories)->name('*.php');

        foreach ($finder as $file) {
            $path = $file->getRealPath();
            $content = $this->files->get($path);
            $originalContent = $content;

            foreach ($this->factoryMapping as $fileName => $domain) {
                $oldUse = "use Database\\Factories\\{$fileName};";
                $newUse = "use Database\\Factories\\{$domain}\\{$fileName};";
                $content = str_replace($oldUse, $newUse, $content);

                $oldInline = "Database\\Factories\\{$fileName}";
                $newInline = "Database\\Factories\\{$domain}\\{$fileName}";
                $content = str_replace($oldInline, $newInline, $content);
            }

            foreach ($this->seederMapping as $fileName => $domain) {
                $oldUse = "use Database\\Seeders\\{$fileName};";
                $newUse = "use Database\\Seeders\\{$domain}\\{$fileName};";
                $content = str_replace($oldUse, $newUse, $content);

                $oldInline = "Database\\Seeders\\{$fileName}";
                $newInline = "Database\\Seeders\\{$domain}\\{$fileName}";
                $content = str_replace($oldInline, $newInline, $content);
            }

            if ($content !== $originalContent) {
                $this->files->put($path, $content);
            }
        }
    }
}