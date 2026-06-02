<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Admin\Auth\AuthAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UnitTypeController;
use App\Http\Controllers\Admin\UnitDepartmentController;
use App\Http\Controllers\Admin\RatingController;
use App\Http\Controllers\Admin\RatingCategoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\UnitPhotoController;
use App\Http\Controllers\Admin\ModerationLogController;
use App\Http\Controllers\Admin\AdminReplyController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController as AuthForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController as AuthResetPasswordController;
use App\Http\Controllers\Student\Auth\AuthController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\RatingController as StudentRatingController;
use App\Http\Controllers\Student\ReportController as StudentReportController;
use App\Http\Controllers\Student\UnitController as StudentUnitController;
use App\Http\Controllers\Student\ActivityController;
use App\Http\Controllers\Student\Auth\ForgotPasswordController;
use App\Http\Controllers\Student\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|==========================================================================
| PUBLIC ROUTES
| Tidak memerlukan authentication
|==========================================================================
*/

Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::prefix('legal')->name('legal.')->group(function () {
    Route::view('/privacy-policy', 'legal.privacy')->name('privacy');
    Route::view('/terms-of-service', 'legal.terms')->name('terms');
    Route::view('/cookie-policy', 'legal.cookies')->name('cookies');
});

Route::prefix('docs')->name('docs.')->group(function () {
    Route::view('/', 'docs.index')->name('index');
    Route::view('/api', 'docs.api')->name('api');
    Route::view('/user-guide', 'docs.user-guide')->name('user-guide');
    Route::view('/faq', 'docs.faq')->name('faq');
});

Route::prefix('contact')->name('contact.')->group(function () {
    Route::view('/', 'contact.index')->name('index');
    Route::view('/support', 'contact.support')->name('support');
});

Route::prefix('errors')->name('errors.')->group(function () {
    Route::view('/403', 'errors.403')->name('403');
    Route::view('/404', 'errors.404')->name('404');
    Route::view('/419', 'errors.419')->name('419');
    Route::view('/500', 'errors.500')->name('500');
});

/*
|==========================================================================
| GLOBAL AUTHENTICATION ROUTES
|==========================================================================
*/

Route::get('/login', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard.index');
    }
    if (Auth::guard('student')->check()) {
        return redirect()->route('student.units.index');
    }
    return redirect()->route('admin.login');
})->name('login');

Route::post('/logout', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.logout');
    }
    if (Auth::guard('student')->check()) {
        return redirect()->route('student.auth.logout');
    }
    return redirect()->route('home');
})->name('logout');

/*
|==========================================================================
| ADMIN ROUTES
| Role: admin, super_admin, unit
|==========================================================================
*/

Route::prefix('admin')->name('admin.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Admin Guest Routes (Belum Login)
    |--------------------------------------------------------------------------
    */
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthAdminController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthAdminController::class, 'login'])->name('login.submit');

        // Forgot Password
        Route::get('/forgot-password', [AuthForgotPasswordController::class, 'showLinkRequestForm'])
            ->name('password.request');
        Route::post('/forgot-password', [AuthForgotPasswordController::class, 'sendResetLinkEmail'])
            ->name('password.email');

        // Reset Password
        Route::get('/reset-password', [AuthResetPasswordController::class, 'showResetForm'])
            ->name('password.reset');
        Route::post('/reset-password', [AuthResetPasswordController::class, 'reset'])
            ->name('password.update');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Auth Routes (Sudah Login)
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', [AuthAdminController::class, 'logout'])
        ->middleware('auth:admin')
        ->name('logout');

    Route::get('/auth/check', [AuthAdminController::class, 'check'])->name('auth.check');

    /*
    |--------------------------------------------------------------------------
    | Admin Protected Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:admin'])->group(function () {

        /*
        |----------------------------------------------------------------------
        | Admin Profile Management
        |----------------------------------------------------------------------
        */
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [AdminProfileController::class, 'show'])->name('show');
            Route::get('/edit', [AdminProfileController::class, 'edit'])->name('edit');
            Route::put('/update', [AdminProfileController::class, 'update'])->name('update');
            Route::put('/password', [AdminProfileController::class, 'updatePassword'])->name('password.update');
            Route::post('/photo', [AdminProfileController::class, 'updatePhoto'])->name('photo.update');
            Route::delete('/photo', [AdminProfileController::class, 'removePhoto'])->name('photo.remove');
            Route::put('/preferences', [AdminProfileController::class, 'updatePreferences'])->name('preferences');
        });

        /*
        |----------------------------------------------------------------------
        | Dashboard
        |----------------------------------------------------------------------
        */
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('index');
            Route::get('/stats', [DashboardController::class, 'stats'])->name('stats');
            Route::get('/charts', [DashboardController::class, 'charts'])->name('charts');
            Route::get('/overview', [DashboardController::class, 'overview'])->name('overview');
            Route::get('/audit-logs', [DashboardController::class, 'auditLogs'])->name('audit-logs');
            Route::get('/recent-rated', [DashboardController::class, 'recentRated'])->name('recent-rated');
            Route::get('/top-units/{type?}', [DashboardController::class, 'topUnits'])->name('top-units');
            Route::get('/attention-units', [DashboardController::class, 'attentionUnits'])->name('attention-units');
            Route::get('/filter/{filter}', [DashboardController::class, 'getUnitsByFilter'])->name('units.filter');
        });

        /*
        |----------------------------------------------------------------------
        | Unit Type Management
        |----------------------------------------------------------------------
        */
        Route::resource('unit-types', UnitTypeController::class);
        Route::post('/unit-types/reorder', [UnitTypeController::class, 'reorder'])->name('unit-types.reorder');
        Route::get('/unit-types/icons/list', [UnitTypeController::class, 'getIcons'])->name('unit-types.icons');
        Route::get('/unit-types/stats/data', [UnitTypeController::class, 'stats'])->name('unit-types.stats');
        Route::patch('/unit-types/{unitType}/toggle-status', [UnitTypeController::class, 'toggleStatus'])
            ->name('unit-types.toggle-status');

        /*
        |----------------------------------------------------------------------
        | Unit Department Management
        |----------------------------------------------------------------------
        */
        Route::resource('unit-departments', UnitDepartmentController::class);
        Route::patch('/unit-departments/{department}/toggle-status', [UnitDepartmentController::class, 'toggleStatus'])
            ->name('unit-departments.toggle-status');
        Route::get('/unit-departments/stats/data', [UnitDepartmentController::class, 'stats'])
            ->name('unit-departments.stats');

        /*
        |----------------------------------------------------------------------
        | Facility Management
        |----------------------------------------------------------------------
        */
        Route::resource('facilities', FacilityController::class);
        Route::get('/facilities/icons/list', [FacilityController::class, 'getIcons'])->name('facilities.icons');
        Route::get('/facilities/stats/data', [FacilityController::class, 'stats'])->name('facilities.stats');
        Route::get('/facilities/{facility}/units', [FacilityController::class, 'units'])->name('facilities.units');
        Route::get('/facilities/export/data', [FacilityController::class, 'export'])->name('facilities.export');

        /*
        |----------------------------------------------------------------------
        | Unit Management
        |----------------------------------------------------------------------
        */
        Route::prefix('units')->name('units.')->group(function () {
            Route::get('/', [UnitController::class, 'index'])->name('index');
            Route::get('/create', [UnitController::class, 'create'])->name('create');
            Route::post('/', [UnitController::class, 'store'])->name('store');
            Route::get('/trash', [UnitController::class, 'trashed'])->name('trash');
            Route::get('/{unit}', [UnitController::class, 'show'])->name('show');
            Route::get('/{unit}/edit', [UnitController::class, 'edit'])->name('edit');
            Route::put('/{unit}', [UnitController::class, 'update'])->name('update');
            Route::delete('/{unit}', [UnitController::class, 'destroy'])->name('destroy');
            Route::post('/{unit}/restore', [UnitController::class, 'restore'])->name('restore');
            Route::delete('/{unit}/force-delete', [UnitController::class, 'forceDelete'])->name('force-delete');

            Route::post('/bulk/delete', [UnitController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/bulk/activate', [UnitController::class, 'bulkActivate'])->name('bulk-activate');
            Route::post('/bulk/restore', [UnitController::class, 'bulkRestore'])->name('bulk-restore');
            Route::post('/bulk/force-delete', [UnitController::class, 'bulkForceDelete'])->name('bulk-force-delete');

            Route::patch('/{unit}/toggle-status', [UnitController::class, 'toggleStatus'])->name('toggle-status');

            Route::prefix('{unit}/photos')->name('photos.')->group(function () {
                Route::get('/', [UnitPhotoController::class, 'index'])->name('index');
                Route::post('/upload', [UnitPhotoController::class, 'upload'])->name('upload');
                Route::delete('/{photo}', [UnitPhotoController::class, 'destroy'])->name('destroy');
                Route::post('/{photo}/primary', [UnitPhotoController::class, 'setPrimary'])->name('set-primary');
                Route::post('/reorder', [UnitPhotoController::class, 'reorder'])->name('reorder');
            });

            Route::post('/{unit}/facilities/sync', [UnitController::class, 'syncFacilities'])->name('sync-facilities');
        });

        /*
        |----------------------------------------------------------------------
        | Rating Category Management
        |----------------------------------------------------------------------
        */
        Route::resource('rating-categories', RatingCategoryController::class);
        Route::patch('/rating-categories/{category}/toggle-active', [RatingCategoryController::class, 'toggleActive'])
            ->name('rating-categories.toggle-active');
        Route::post('/rating-categories/reorder', [RatingCategoryController::class, 'reorder'])
            ->name('rating-categories.reorder');
        Route::get('/rating-categories/active/list', [RatingCategoryController::class, 'getActive'])
            ->name('rating-categories.active');
        Route::get('/rating-categories/stats/data', [RatingCategoryController::class, 'stats'])
            ->name('rating-categories.stats');
        Route::post('/rating-categories/seed-defaults', [RatingCategoryController::class, 'seedDefaults'])
            ->name('rating-categories.seed-defaults');
        Route::post('/rating-categories/validate-for-rating', [RatingCategoryController::class, 'validateForRating'])
            ->name('rating-categories.validate');
        Route::get('/rating-categories/export/data', [RatingCategoryController::class, 'export'])
            ->name('rating-categories.export');

        /*
        |----------------------------------------------------------------------
        | Rating Management
        |----------------------------------------------------------------------
        */
        Route::prefix('ratings')->name('ratings.')->group(function () {
            Route::get('/', [RatingController::class, 'index'])->name('index');
            Route::get('/stats/overview', [RatingController::class, 'stats'])->name('stats');
            Route::get('/unit/{unitId}/stats', [RatingController::class, 'unitStats'])->name('unit-stats');
            Route::get('/export/data', [RatingController::class, 'export'])->name('export');
            Route::get('/{rating}', [RatingController::class, 'show'])->name('show');
            Route::patch('/{rating}/status', [RatingController::class, 'updateStatus'])->name('update-status');
            Route::post('/{rating}/moderate', [RatingController::class, 'moderate'])->name('moderate');
            Route::post('/bulk-action', [RatingController::class, 'bulkAction'])->name('bulk-action');
            Route::delete('/{rating}', [RatingController::class, 'destroy'])->name('destroy');
        });

        /*
        |----------------------------------------------------------------------
        | Admin Replies Management
        |----------------------------------------------------------------------
        */
        Route::prefix('admin-replies')->name('admin-replies.')->group(function () {
            Route::get('/', [AdminReplyController::class, 'index'])->name('index');
            Route::get('/stats/data', [AdminReplyController::class, 'stats'])->name('stats');
            Route::get('/export/data', [AdminReplyController::class, 'export'])->name('export');
            Route::get('/unit/{unitId}/recent', [AdminReplyController::class, 'recentForUnit'])->name('recent');
            Route::get('/check/{rating}/can-reply', [AdminReplyController::class, 'checkCanReply'])->name('check');
            Route::post('/{rating}/reply', [AdminReplyController::class, 'store'])->name('store');
            Route::get('/{adminReply}', [AdminReplyController::class, 'show'])->name('show');
            Route::get('/{adminReply}/edit', [AdminReplyController::class, 'edit'])->name('edit');
            Route::put('/{adminReply}', [AdminReplyController::class, 'update'])->name('update');
            Route::delete('/{adminReply}', [AdminReplyController::class, 'destroy'])->name('destroy');
        });

        /*
        |----------------------------------------------------------------------
        | Report Management
        |----------------------------------------------------------------------
        */
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/stats/overview', [ReportController::class, 'stats'])->name('stats');
            Route::get('/unit/{unitId}/stats', [ReportController::class, 'unitStats'])->name('unit-stats');
            Route::get('/export/data', [ReportController::class, 'export'])->name('export');
            Route::get('/{report}', [ReportController::class, 'show'])->name('show');
            Route::get('/{report}/edit', [ReportController::class, 'edit'])->name('edit');
            Route::put('/{report}', [ReportController::class, 'update'])->name('update');
            Route::put('/{report}/status', [ReportController::class, 'updateStatus'])->name('update-status');
            Route::post('/{report}/reply', [ReportController::class, 'reply'])->name('reply');
            Route::post('/bulk-action', [ReportController::class, 'bulkAction'])->name('bulk-action');
            Route::delete('/{report}', [ReportController::class, 'destroy'])->name('destroy');
        });

        /*
        |----------------------------------------------------------------------
        | Moderation Logs Management
        |----------------------------------------------------------------------
        */
        Route::prefix('moderation-logs')->name('moderation-logs.')->group(function () {
            Route::get('/', [ModerationLogController::class, 'index'])->name('index');
            Route::get('/stats/overview', [ModerationLogController::class, 'stats'])->name('stats');
            Route::get('/by-target/{targetType}/{targetId}', [ModerationLogController::class, 'byTarget'])->name('by-target');
            Route::get('/by-admin/{adminId}', [ModerationLogController::class, 'byAdmin'])->name('by-admin');
            Route::get('/summary', [ModerationLogController::class, 'summary'])->name('summary');
            Route::get('/export/data', [ModerationLogController::class, 'export'])->name('export');
            Route::get('/{log}', [ModerationLogController::class, 'show'])->name('show');
            Route::post('/cleanup', [ModerationLogController::class, 'cleanup'])->name('cleanup');
            Route::delete('/{log}', [ModerationLogController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-destroy', [ModerationLogController::class, 'bulkDestroy'])->name('bulk-destroy');
        });

        /*
        |----------------------------------------------------------------------
        | Settings Management
        |----------------------------------------------------------------------
        */
        // Route::prefix('settings')->name('settings.')->group(function () {
        //     Route::get('/', [SettingController::class, 'index'])->name('index');
        //     Route::get('/create', [SettingController::class, 'create'])->name('create');
        //     Route::post('/', [SettingController::class, 'store'])->name('store');
        //     Route::get('/group/{group}', [SettingController::class, 'getByGroup'])->name('group');
        //     Route::get('/public/all', [SettingController::class, 'getPublic'])->name('public');
        //     Route::get('/value/{key}', [SettingController::class, 'getValue'])->name('value');
        //     Route::get('/stats/data', [SettingController::class, 'stats'])->name('stats');
        //     Route::get('/export/data', [SettingController::class, 'export'])->name('export');
        //     Route::get('/{setting}', [SettingController::class, 'show'])->name('show');
        //     Route::get('/{setting}/edit', [SettingController::class, 'edit'])->name('edit');
        //     Route::put('/{setting}', [SettingController::class, 'update'])->name('update');
        //     Route::delete('/{setting}', [SettingController::class, 'destroy'])->name('destroy');
        //     Route::post('/bulk-update', [SettingController::class, 'bulkUpdate'])->name('bulk-update');
        //     Route::post('/reset/{key}', [SettingController::class, 'resetToDefault'])->name('reset');
        //     Route::post('/import', [SettingController::class, 'import'])->name('import');

        //     Route::get('/group/{group}/show', [SettingController::class, 'index'])->name('group.show');
        //     Route::put('/group/{group}/update', [SettingController::class, 'updateGroup'])->name('group.update');
        //     Route::post('/group/{group}/reset', [SettingController::class, 'resetGroup'])->name('group.reset');

        //     Route::post('/refresh-cache', [SettingController::class, 'refreshCache'])->name('refresh.cache');
        // });

        /*
        |----------------------------------------------------------------------
        | Export Management
        |----------------------------------------------------------------------
        */
        Route::prefix('exports')->name('exports.')->group(function () {
            Route::get('/', [ExportController::class, 'index'])->name('index');
            Route::post('/reports', [ExportController::class, 'exportReports'])->name('reports');
            Route::post('/ratings', [ExportController::class, 'exportRatings'])->name('ratings');
            Route::post('/units', [ExportController::class, 'exportUnits'])->name('units');
            Route::post('/unit-types', [ExportController::class, 'exportUnitTypes'])->name('unit-types');
            Route::get('/download/{id}', [ExportController::class, 'downloadExport'])->name('download');
        });
    });
});

/*
|==========================================================================
| STUDENT ROUTES
| Role: student
|==========================================================================
*/

Route::prefix('student')->name('student.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Public Unit Browsing (Tanpa Login)
    |--------------------------------------------------------------------------
    */
    Route::prefix('units')->name('units.')->group(function () {
        Route::get('/', [StudentUnitController::class, 'index'])->name('index');
        Route::get('/{unit:slug}', [StudentUnitController::class, 'show'])->name('show');
    });

    /*
    |--------------------------------------------------------------------------
    | Student Guest Routes (Belum Login)
    |--------------------------------------------------------------------------
    */
    Route::middleware('guest:student')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
        Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
        Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
            ->name('password.request');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
            ->name('password.email');

        // Reset Password
        Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])
            ->name('password.reset');
        Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
            ->name('password.update');
    });

    /*
    |--------------------------------------------------------------------------
    | Student Protected Routes (Sudah Login)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:student')->group(function () {

        /*
        |----------------------------------------------------------------------
        | Student Dashboard
        |----------------------------------------------------------------------
        */
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/', [StudentDashboardController::class, 'index'])->name('index');
        });

        /*
        |----------------------------------------------------------------------
        | Student Rating Management
        |----------------------------------------------------------------------
        */
        Route::prefix('ratings')->name('ratings.')->group(function () {
            Route::get('/history/all', [StudentRatingController::class, 'history'])->name('history');
            Route::get('/categories/list', [StudentRatingController::class, 'categories'])->name('categories');
            Route::get('/create/{unit:slug}', [StudentRatingController::class, 'create'])->name('create');
            Route::post('/', [StudentRatingController::class, 'store'])->name('store');
            Route::get('/unit/{unit:slug}/ratings', [StudentRatingController::class, 'unitRatings'])->name('unit');
            Route::get('/{trackingCode}', [StudentRatingController::class, 'show'])->name('show');
            Route::get('/{trackingCode}/edit', [StudentRatingController::class, 'edit'])->name('edit');
            Route::put('/{trackingCode}', [StudentRatingController::class, 'update'])->name('update');
        });

        /*
        |----------------------------------------------------------------------
        | Student Report Management
        |----------------------------------------------------------------------
        */
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/history/all', [StudentReportController::class, 'history'])->name('history');
            Route::get('/check/{rating}/can-report', [StudentReportController::class, 'checkCanReport'])
                ->name('check-can-report');
            Route::get('/create/{rating}', [StudentReportController::class, 'create'])->name('create');
            Route::post('/', [StudentReportController::class, 'store'])->name('store');
            Route::get('/{trackingCode}', [StudentReportController::class, 'show'])->name('show');
        });

        /*
        |----------------------------------------------------------------------
        | Student Profile Management
        |----------------------------------------------------------------------
        */
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [StudentProfileController::class, 'show'])->name('show');
            Route::get('/edit', [StudentProfileController::class, 'edit'])->name('edit');
            Route::put('/', [StudentProfileController::class, 'update'])->name('update');
            Route::get('/sessions', [StudentProfileController::class, 'sessions'])->name('sessions');
            Route::delete('/sessions/{sessionId}', [StudentProfileController::class, 'terminateSession'])
                ->name('terminate-session');
            Route::delete('/sessions/all/terminate', [StudentProfileController::class, 'terminateAllSessions'])
                ->name('terminate-all');
            Route::post('/favorite-unit/{unitId}', [StudentProfileController::class, 'toggleFavorite'])
                ->name('toggle-favorite');
        });

        /*
        |----------------------------------------------------------------------
        | Student Activities
        |----------------------------------------------------------------------
        */
        Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');

        /*
        |----------------------------------------------------------------------
        | Student Favorite Unit (Alternative)
        |----------------------------------------------------------------------
        */
        Route::post('/units/{unitId}/favorite', [StudentUnitController::class, 'toggleFavorite'])
            ->name('units.favorite');

        /*
        |----------------------------------------------------------------------
        | Student Auth (Logout & Check)
        |----------------------------------------------------------------------
        */
        Route::prefix('auth')->name('auth.')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('/check', [AuthController::class, 'check'])->name('check');
        });
    });
});

/*
|==========================================================================
| FALLBACK ROUTE (404 Not Found)
|==========================================================================
*/

Route::fallback(function () {
    return view('errors.404');
});
