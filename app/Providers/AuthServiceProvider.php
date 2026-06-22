<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

use App\Models\Unit\UnitType;
use App\Models\Unit\Unit;
use App\Models\Unit\UnitPhoto;
use App\Models\Unit\UnitDepartment;
use App\Models\Unit\Facility;
use App\Models\Unit\QrCode;
use App\Models\Feedback\Rating;
use App\Models\Feedback\RatingCategory;
use App\Models\Report\Report;
use App\Models\Report\ReportCategory;
use App\Models\System\Setting;
use App\Models\System\Export;
use App\Models\System\ModerationLog;
use App\Models\System\Notification;
use App\Models\Feedback\UnitVisit;
use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Student;

use App\Policies\UnitTypePolicy;
use App\Policies\UnitPolicy;
use App\Policies\UnitPhotoPolicy;
use App\Policies\UnitDepartmentPolicy;
use App\Policies\FacilityPolicy;
use App\Policies\QrCodePolicy;
use App\Policies\RatingPolicy;
use App\Policies\RatingCategoryPolicy;
use App\Policies\ReportPolicy;
use App\Policies\ReportCategoryPolicy;
use App\Policies\SettingPolicy;
use App\Policies\ExportPolicy;
use App\Policies\ModerationLogPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\UnitVisitPolicy;
use App\Policies\AdminPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\StudentPolicy;
use App\Policies\DashboardPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        UnitType::class => UnitTypePolicy::class,
        Unit::class => UnitPolicy::class,
        UnitPhoto::class => UnitPhotoPolicy::class,
        UnitDepartment::class => UnitDepartmentPolicy::class,
        Facility::class => FacilityPolicy::class,
        QrCode::class => QrCodePolicy::class,
        Rating::class => RatingPolicy::class,
        RatingCategory::class => RatingCategoryPolicy::class,
        Report::class => ReportPolicy::class,
        ReportCategory::class => ReportCategoryPolicy::class,
        Setting::class => SettingPolicy::class,
        Export::class => ExportPolicy::class,
        ModerationLog::class => ModerationLogPolicy::class,
        Notification::class => NotificationPolicy::class,
        UnitVisit::class => UnitVisitPolicy::class,
        Admin::class => AdminPolicy::class,
        Employee::class => EmployeePolicy::class,
        Student::class => StudentPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        config([
            'auth.guards' => [
                'admin' => [
                    'driver' => 'session',
                    'provider' => 'admins',
                ],
                'employee' => [
                    'driver' => 'session',
                    'provider' => 'employees',
                ],
                'student' => [
                    'driver' => 'session',
                    'provider' => 'students',
                ],
            ],
            'auth.defaults.guard' => 'admin',
        ]);
    }
}