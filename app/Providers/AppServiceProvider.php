<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerBladeComponents();
    }

    public function register(): void
    {
        $this->registerSharedServices();
        $this->registerAdminServices();
        $this->registerStudentServices();
        $this->loadSettingsHelpers();
    }

    protected function registerBladeComponents(): void
    {
    
    }

    protected function registerSharedServices(): void
    {
        $this->app->singleton(\App\Services\Shared\BaseService::class);
    }

    protected function registerAdminServices(): void
    {
        $this->app->singleton(\App\Services\Admin\BaseAdminService::class);
        $this->app->singleton(\App\Services\Admin\AdminProfileService::class);
        $this->app->singleton(\App\Services\Admin\DashboardService::class);
        $this->app->singleton(\App\Services\Admin\UnitService::class);
        $this->app->singleton(\App\Services\Admin\UnitTypeService::class);
        $this->app->singleton(\App\Services\Admin\UnitDepartmentService::class);
        $this->app->singleton(\App\Services\Admin\FacilityService::class);
        $this->app->singleton(\App\Services\Admin\UnitPhotoService::class);
        $this->app->singleton(\App\Services\Admin\RatingManagementService::class);
        $this->app->singleton(\App\Services\Admin\RatingCategoryService::class);
        $this->app->singleton(\App\Services\Admin\AdminReplyService::class);
        $this->app->singleton(\App\Services\Admin\ReportManagementService::class);
        $this->app->singleton(\App\Services\Admin\ModerationLogService::class);
        $this->app->singleton(\App\Services\Admin\SettingService::class);
        $this->app->singleton(\App\Services\Admin\ExportDataService::class);
    }

    protected function registerStudentServices(): void
    {
        $this->app->singleton(\App\Services\Student\DashboardService::class);
        $this->app->singleton(\App\Services\Student\RatingService::class);
        $this->app->singleton(\App\Services\Student\ReportService::class);
        $this->app->singleton(\App\Services\Student\ProfileService::class);
    }

    protected function loadSettingsHelpers(): void
    {
        $helperFile = app_path('Helpers/settings.php');

        if (file_exists($helperFile)) {
            require_once $helperFile;
        }
    }
}