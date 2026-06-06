<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class AddFactoryMethodToModels extends Command
{
    protected $signature = 'models:add-factory-method {--force : Force update}';

    protected $description = 'Add newFactory method to all models';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $this->info('Adding factory method to models...');

        $models = [
            'Authentication/Admin.php' => 'AdminFactory',
            'Authentication/Student.php' => 'StudentFactory',
            'Authentication/Employee.php' => 'EmployeeFactory',
            'Units/Unit.php' => 'UnitFactory',
            'Units/UnitType.php' => 'UnitTypeFactory',
            'Units/UnitDepartment.php' => 'UnitDepartmentFactory',
            'Units/Facility.php' => 'FacilityFactory',
            'Units/UnitPhoto.php' => 'UnitPhotoFactory',
            'Units/QrCode.php' => 'QrCodeFactory',
            'Feedback/Rating.php' => 'RatingFactory',
            'Feedback/RatingCategory.php' => 'RatingCategoryFactory',
            'Feedback/RatingScore.php' => 'RatingScoreFactory',
            'Feedback/RatingAttachment.php' => 'RatingAttachmentFactory',
            'Feedback/RatingReply.php' => 'RatingReplyFactory',
            'Feedback/UnitVisit.php' => 'UnitVisitFactory',
            'Reports/Report.php' => 'ReportFactory',
            'Reports/ReportCategory.php' => 'ReportCategoryFactory',
            'Reports/ReportAttachment.php' => 'ReportAttachmentFactory',
            'Reports/ReportReply.php' => 'ReportReplyFactory',
            'Reports/ReportStatusHistory.php' => 'ReportStatusHistoryFactory',
            'Employee/EmployeePosition.php' => 'EmployeePositionFactory',
            'Employee/EmployeeUnitAssignment.php' => 'EmployeeUnitAssignmentFactory',
            'System/Notification.php' => 'NotificationFactory',
            'System/Setting.php' => 'SettingFactory',
            'System/Export.php' => 'ExportFactory',
            'System/ModerationLog.php' => 'ModerationLogFactory',
        ];

        $basePath = app_path('Models');

        foreach ($models as $path => $factoryName) {
            $fullPath = $basePath . '/' . $path;

            if (!File::exists($fullPath)) {
                $this->warn("Model not found: {$path}");
                continue;
            }

            $content = File::get($fullPath);

            $factoryMethod = <<<PHP

    protected static function newFactory()
    {
        return \\Database\\Factories\\{$factoryName}::new();
    }
PHP;

            $lastBracketPos = strrpos($content, '}');
            if ($lastBracketPos !== false) {
                $content = substr_replace($content, $factoryMethod . "\n}", $lastBracketPos, 1);
                File::put($fullPath, $content);
                $this->info("Updated: {$path}");
            }
        }

        $this->info('All models updated successfully.');

        return Command::SUCCESS;
    }
}