<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        $this->registerBladeDirectives();
        $this->registerViewComposers();
        $this->registerPolicies();
        $this->registerModelObservers();
    }

    public function register(): void
    {
        $this->bindExportServices();
        $this->loadSettingsHelpers();
    }

    protected function registerBladeDirectives(): void
    {
        Blade::if('admin', fn() => auth('admin')->check());
        Blade::if('employee', fn() => auth('employee')->check());
        Blade::if('student', fn() => auth('student')->check());
        Blade::if('superadmin', function () {
            $user = auth('admin')->user();
            return $user && $user->role === 'super_admin';
        });
    }

    protected function registerViewComposers(): void
    {
        view()->composer('*', fn($view) => $view->with('appName', config('app.name')));

        view()->composer('layouts.admin', function ($view) {
            $view->with('currentAdmin', auth('admin')->user());
        });

        view()->composer('layouts.employee', function ($view) {
            $employee = auth('employee')->user();
            $unreadCount = 0;
            if ($employee && method_exists($employee, 'notifications')) {
                $unreadCount = $employee->notifications()->whereNull('read_at')->count();
            }
            $view->with('currentEmployee', $employee)->with('unreadNotifications', $unreadCount);
        });

        view()->composer('layouts.student', function ($view) {
            $student = auth('student')->user();
            $unreadCount = 0;
            if ($student && method_exists($student, 'notifications')) {
                $unreadCount = $student->notifications()->whereNull('read_at')->count();
            }
            $view->with('currentStudent', $student)->with('unreadNotifications', $unreadCount);
        });
    }

    protected function registerPolicies(): void
    {
        $policies = [
            \App\Models\Authentication\Admin::class => \App\Policies\AdminPolicy::class,
            \App\Models\Authentication\Employee::class => \App\Policies\EmployeePolicy::class,
            \App\Models\Authentication\Student::class => \App\Policies\StudentPolicy::class,
            \App\Models\Unit\Unit::class => \App\Policies\UnitPolicy::class,
            \App\Models\Unit\UnitType::class => \App\Policies\UnitTypePolicy::class,
            \App\Models\Unit\UnitDepartment::class => \App\Policies\UnitDepartmentPolicy::class,
            \App\Models\Unit\Facility::class => \App\Policies\FacilityPolicy::class,
            \App\Models\Unit\UnitPhoto::class => \App\Policies\UnitPhotoPolicy::class,
            \App\Models\Unit\QrCode::class => \App\Policies\QrCodePolicy::class,
            \App\Models\Feedback\Rating::class => \App\Policies\RatingPolicy::class,
            \App\Models\Feedback\RatingCategory::class => \App\Policies\RatingCategoryPolicy::class,
            \App\Models\Report\Report::class => \App\Policies\ReportPolicy::class,
            \App\Models\Report\ReportCategory::class => \App\Policies\ReportCategoryPolicy::class,
            \App\Models\System\Setting::class => \App\Policies\SettingPolicy::class,
            \App\Models\System\Export::class => \App\Policies\ExportPolicy::class,
            \App\Models\System\ModerationLog::class => \App\Policies\ModerationLogPolicy::class,
            \App\Models\System\Notification::class => \App\Policies\NotificationPolicy::class
    ];

        foreach ($policies as $model => $policy) {
            if (class_exists($model) && class_exists($policy)) {
                Gate::policy($model, $policy);
            }
        }
    }

    protected function registerModelObservers(): void
    {
        $observers = [
            \App\Models\Feedback\Rating::class => \App\Observers\RatingObserver::class,
            \App\Models\Report\Report::class => \App\Observers\ReportObserver::class,
            \App\Models\Unit\Unit::class => \App\Observers\UnitObserver::class,
            \App\Models\Unit\UnitType::class => \App\Observers\UnitTypeObserver::class,
            \App\Models\Unit\UnitDepartment::class => \App\Observers\UnitDepartmentObserver::class,
            \App\Models\Unit\Facility::class => \App\Observers\FacilityObserver::class
    ];

        foreach ($observers as $model => $observer) {
            if (class_exists($model) && class_exists($observer)) {
                $model::observe($observer);
            }
        }
    }

    protected function bindExportServices(): void
    {
        $this->app->bind(
            \App\Services\Export\Contracts\ExportInterface::class,
            \App\Services\Export\ExportManager::class
        );
    }

    protected function loadSettingsHelpers(): void
    {
        $helperFile = app_path('Helpers/settings.php');
        if (file_exists($helperFile)) {
            require_once $helperFile;
        }
    }
}