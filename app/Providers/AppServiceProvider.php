<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Student;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        $this->registerBladeDirectives();
        $this->registerViewComposers();
    }

    public function register(): void
    {
        $this->bindExportServices();
        $this->loadSettingsHelpers();
    }

    protected function registerBladeDirectives(): void
    {
        Blade::if('admin', function () {
            return auth('admin')->check();
        });

        Blade::if('employee', function () {
            return auth('employee')->check();
        });

        Blade::if('student', function () {
            return auth('student')->check();
        });

        Blade::if('superadmin', function () {
            $user = auth('admin')->user();
            return $user && $user->role === 'super_admin';
        });
    }

    protected function registerViewComposers(): void
    {
        view()->composer('*', function ($view) {
            $view->with('appName', config('app.name'));
        });

        view()->composer('layouts.admin', function ($view) {
            $admin = auth('admin')->user();
            $view->with('currentAdmin', $admin);
        });

        view()->composer('layouts.employee', function ($view) {
            $employee = auth('employee')->user();
            $unreadCount = 0;

            if ($employee && method_exists($employee, 'notifications')) {
                $unreadCount = $employee->notifications()->whereNull('read_at')->count();
            }

            $view->with('currentEmployee', $employee)
                ->with('unreadNotifications', $unreadCount);
        });

        view()->composer('layouts.student', function ($view) {
            $student = auth('student')->user();
            $unreadCount = 0;

            if ($student && method_exists($student, 'notifications')) {
                $unreadCount = $student->notifications()->whereNull('read_at')->count();
            }

            $view->with('currentStudent', $student)
                ->with('unreadNotifications', $unreadCount);
        });
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
