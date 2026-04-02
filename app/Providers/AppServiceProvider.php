<?php

namespace App\Providers;

use App\View\Components\Admin\DeleteModal;
use \App\View\Components\Admin\FilterButton;
use \App\View\Components\Admin\PerPageDropdown;
use \App\View\Components\Admin\SortDropdown;

use App\View\Components\admin\alert;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register blade components
        Blade::component('admin.pagination', \App\View\Components\Admin\Pagination::class);
        Blade::component('admin.delete-modal', DeleteModal::class);

        // Register new admin components
        Blade::component('admin.filter-button', FilterButton::class);
        Blade::component('admin.per-page-dropdown', PerPageDropdown::class);
        Blade::component('admin.sort-dropdown', SortDropdown::class);
        Blade::component('admin.alert', alert::class);
        Blade::component('admin.empty-state', \App\View\Components\Admin\EmptyState::class);
        Blade::component('admin-filter-status', \App\View\Components\Admin\FilterStatusButton::class);
        Blade::component('admin-status-filter-dropdown', \App\View\Components\Admin\StatusFilterDropdown::class);

        // NO OBSERVERS - sesuai keinginan kamu (logic di service aja)
    }

    public function register(): void
    {
        $this->registerSharedServices();
        $this->registerAdminServices();
        $this->registerStudentServices();
        // $this->registerAnalyticsServices();
        // $this->registerCacheServices();
        // $this->registerLoggingServices();
        // $this->registerNotificationServices();
        // $this->registerSecurityServices();
        // $this->registerExportServices();
        $this->loadSettingsHelpers();
    }

    protected function registerSharedServices(): void
    {
        // $this->app->singleton(\App\Services\Shared\CacheService::class);
        // $this->app->singleton(\App\Services\Shared\ImageService::class);
        // $this->app->singleton(\App\Services\Shared\NotificationService::class);
        $this->app->singleton(\App\Services\Shared\BaseService::class);
    }

    protected function registerAdminServices(): void
    {
        // Core Admin Services
        $this->app->singleton(\App\Services\Admin\DashboardService::class);
        // $this->app->singleton(\App\Services\Admin\ChartService::class);

        // Unit Management
        $this->app->singleton(\App\Services\Admin\UnitService::class);
        $this->app->singleton(\App\Services\Admin\UnitTypeService::class);
        $this->app->singleton(\App\Services\Admin\UnitDepartmentService::class);
        $this->app->singleton(\App\Services\Admin\FacilityService::class);
        $this->app->singleton(\App\Services\Admin\UnitPhotoService::class);

        // Rating & Feedback
        $this->app->singleton(\App\Services\Admin\RatingManagementService::class);
        $this->app->singleton(\App\Services\Admin\RatingCategoryService::class);
        $this->app->singleton(\App\Services\Admin\AdminReplyService::class);
        $this->app->singleton(\App\Services\Admin\ReportManagementService::class);

        // System & Monitoring
        $this->app->singleton(\App\Services\Admin\ModerationLogService::class);
        $this->app->singleton(\App\Services\Admin\SettingService::class);
        // $this->app->singleton(\App\Services\Admin\StudentManagementService::class);

        // Export Services (Admin specific)
        $this->app->singleton(\App\Services\Admin\ExportDataService::class);
    }

    protected function registerStudentServices(): void
    {
        $this->app->singleton(\App\Services\Student\DashboardService::class);
        $this->app->singleton(\App\Services\Student\RatingService::class);
        $this->app->singleton(\App\Services\Student\ReportService::class);
        $this->app->singleton(\App\Services\Student\ProfileService::class);
        $this->app->singleton(\App\Services\Student\StudentSessionService::class);
        // $this->app->singleton(\App\Services\Student\BrowseService::class);
    }

    // protected function registerAnalyticsServices(): void
    // {
    //     $this->app->singleton(\App\Services\Analytics\RatingAnalyticsService::class);
    //     $this->app->singleton(\App\Services\Analytics\UnitAnalyticsService::class);
    //     $this->app->singleton(\App\Services\Analytics\ReportAnalyticsService::class);
    //     $this->app->singleton(\App\Services\Analytics\VisitorAnalyticsService::class);
    //     $this->app->singleton(\App\Services\Analytics\TrendAnalysisService::class);
    // }

    // protected function registerCacheServices(): void
    // {
    //     $this->app->singleton(\App\Services\Cache\CacheInvalidationService::class);
    //     $this->app->singleton(\App\Services\Cache\CacheKeyGenerator::class);
    //     $this->app->singleton(\App\Services\Cache\DashboardCacheManager::class);
    //     $this->app->singleton(\App\Services\Cache\StatisticsCacheService::class);
    //     $this->app->singleton(\App\Services\Cache\UnitCacheService::class);
    //     $this->app->singleton(\App\Services\Cache\RatingCacheService::class);
    // }

    // protected function registerLoggingServices(): void
    // {
    //     $this->app->singleton(\App\Services\Logging\AdminActionLogger::class);
    //     $this->app->singleton(\App\Services\Logging\UserActivityLogger::class);
    //     $this->app->singleton(\App\Services\Logging\ErrorLogger::class);
    //     $this->app->singleton(\App\Services\Logging\AuditLogger::class);
    // }

    // protected function registerNotificationServices(): void
    // {
    //     $this->app->singleton(\App\Services\Notification\EmailService::class);
    //     $this->app->singleton(\App\Services\Notification\TemplateService::class);
    //     $this->app->singleton(\App\Services\Notification\PushNotificationService::class);
    //     $this->app->singleton(\App\Services\Notification\InAppNotificationService::class);
    //     $this->app->singleton(\App\Services\Notification\NotificationManager::class);
    // }

    // protected function registerSecurityServices(): void
    // {
    //     $this->app->singleton(\App\Services\Security\RateLimitService::class);
    //     $this->app->singleton(\App\Services\Security\ValidationService::class);
    //     $this->app->singleton(\App\Services\Security\PermissionService::class);
    //     $this->app->singleton(\App\Services\Security\RoleService::class);
    //     $this->app->singleton(\App\Services\Security\AuthenticationService::class);
    // }

    // protected function registerExportServices(): void
    // {
    //     // Export Format Services
    //     $this->app->singleton(\App\Services\Export\ExcelExportService::class);
    //     $this->app->singleton(\App\Services\Export\PdfExportService::class);
    //     $this->app->singleton(\App\Services\Export\CsvExportService::class);
    //     $this->app->singleton(\App\Services\Export\JsonExportService::class);

    //     // Export Generators
    //     $this->app->singleton(\App\Services\Export\Exports\ReportExport::class);
    //     $this->app->singleton(\App\Services\Export\Exports\RatingExport::class);
    //     $this->app->singleton(\App\Services\Export\Exports\UnitExport::class);
    //     $this->app->singleton(\App\Services\Export\Exports\StudentExport::class);
    //     $this->app->singleton(\App\Services\Export\Exports\UnitTypeExport::class);

    //     // Export Management
    //     $this->app->singleton(\App\Services\Export\ExportQueueService::class);
    //     $this->app->singleton(\App\Services\Export\ReportGeneratorService::class);
    //     $this->app->singleton(\App\Services\Export\ExportManager::class);
    //     $this->app->singleton(\App\Services\Export\ExportHistoryService::class);
    // }

    protected function loadSettingsHelpers(): void
    {
        $helperFile = app_path('Helpers/settings.php');

        if (file_exists($helperFile)) {
            require_once $helperFile;
        }
    }
}
