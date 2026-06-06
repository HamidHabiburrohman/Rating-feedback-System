<?php

use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController as AdminForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController as AdminResetPasswordController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\UnitController as AdminUnitController;
use App\Http\Controllers\Admin\UnitTypeController as AdminUnitTypeController;
use App\Http\Controllers\Admin\UnitDepartmentController as AdminUnitDepartmentController;
use App\Http\Controllers\Admin\UnitPhotoController as AdminUnitPhotoController;
use App\Http\Controllers\Admin\RatingController as AdminRatingController;
use App\Http\Controllers\Admin\RatingCategoryController as AdminRatingCategoryController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Admin\ExportController as AdminExportController;
use App\Http\Controllers\Admin\ModerationLogController as AdminModerationLogController;
use App\Http\Controllers\Admin\EmployeeController as AdminEmployeeController;
use App\Http\Controllers\Admin\QrCodeController as AdminQrCodeController;
use App\Http\Controllers\Admin\ReportCategoryController as AdminReportCategoryController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');
        Route::get('/forgot-password', [AdminForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/forgot-password', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/reset-password', [AdminResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [AdminResetPasswordController::class, 'reset'])->name('password.update');
    });

    Route::post('/logout', [AdminLoginController::class, 'logout'])->middleware('auth:admin')->name('logout');
    Route::get('/auth/check', [AdminLoginController::class, 'check'])->middleware('auth:admin')->name('auth.check');

    Route::middleware(['auth:admin'])->group(function () {
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [AdminProfileController::class, 'show'])->name('show');
            Route::get('/edit', [AdminProfileController::class, 'edit'])->name('edit');
            Route::put('/update', [AdminProfileController::class, 'update'])->name('update');
            Route::put('/password', [AdminProfileController::class, 'updatePassword'])->name('password.update');
            Route::post('/photo', [AdminProfileController::class, 'updatePhoto'])->name('photo.update');
            Route::delete('/photo', [AdminProfileController::class, 'removePhoto'])->name('photo.remove');
            Route::put('/preferences', [AdminProfileController::class, 'updatePreferences'])->name('preferences');
        });

        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/', [AdminDashboardController::class, 'index'])->name('index');
            Route::get('/stats', [AdminDashboardController::class, 'stats'])->name('stats');
            Route::get('/charts', [AdminDashboardController::class, 'charts'])->name('charts');
            Route::get('/overview', [AdminDashboardController::class, 'overview'])->name('overview');
            Route::get('/audit-logs', [AdminDashboardController::class, 'auditLogs'])->name('audit-logs');
            Route::get('/recent-rated', [AdminDashboardController::class, 'recentRated'])->name('recent-rated');
            Route::get('/top-units/{type?}', [AdminDashboardController::class, 'topUnits'])->name('top-units');
            Route::get('/attention-units', [AdminDashboardController::class, 'attentionUnits'])->name('attention-units');
            Route::get('/filter/{filter}', [AdminDashboardController::class, 'getUnitsByFilter'])->name('units.filter');
        });

        Route::resource('unit-types', AdminUnitTypeController::class);
        Route::post('/unit-types/reorder', [AdminUnitTypeController::class, 'reorder'])->name('unit-types.reorder');
        Route::get('/unit-types/icons/list', [AdminUnitTypeController::class, 'getIcons'])->name('unit-types.icons');
        Route::get('/unit-types/stats/data', [AdminUnitTypeController::class, 'stats'])->name('unit-types.stats');
        Route::patch('/unit-types/{unitType}/toggle-status', [AdminUnitTypeController::class, 'toggleStatus'])->name('unit-types.toggle-status');

        Route::resource('unit-departments', AdminUnitDepartmentController::class);
        Route::patch('/unit-departments/{department}/toggle-status', [AdminUnitDepartmentController::class, 'toggleStatus'])->name('unit-departments.toggle-status');
        Route::get('/unit-departments/stats/data', [AdminUnitDepartmentController::class, 'stats'])->name('unit-departments.stats');

        Route::resource('facilities', AdminFacilityController::class);
        Route::get('/facilities/icons/list', [AdminFacilityController::class, 'getIcons'])->name('facilities.icons');
        Route::get('/facilities/stats/data', [AdminFacilityController::class, 'stats'])->name('facilities.stats');
        Route::get('/facilities/{facility}/units', [AdminFacilityController::class, 'units'])->name('facilities.units');
        Route::get('/facilities/export/data', [AdminFacilityController::class, 'export'])->name('facilities.export');

        Route::prefix('units')->name('units.')->group(function () {
            Route::get('/', [AdminUnitController::class, 'index'])->name('index');
            Route::get('/create', [AdminUnitController::class, 'create'])->name('create');
            Route::post('/', [AdminUnitController::class, 'store'])->name('store');
            Route::get('/trash', [AdminUnitController::class, 'trashed'])->name('trash');
            Route::get('/{unit}', [AdminUnitController::class, 'show'])->name('show');
            Route::get('/{unit}/edit', [AdminUnitController::class, 'edit'])->name('edit');
            Route::put('/{unit}', [AdminUnitController::class, 'update'])->name('update');
            Route::delete('/{unit}', [AdminUnitController::class, 'destroy'])->name('destroy');
            Route::post('/{unit}/restore', [AdminUnitController::class, 'restore'])->name('restore');
            Route::delete('/{unit}/force-delete', [AdminUnitController::class, 'forceDelete'])->name('force-delete');
            Route::post('/bulk/delete', [AdminUnitController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/bulk/activate', [AdminUnitController::class, 'bulkActivate'])->name('bulk-activate');
            Route::post('/bulk/restore', [AdminUnitController::class, 'bulkRestore'])->name('bulk-restore');
            Route::post('/bulk/force-delete', [AdminUnitController::class, 'bulkForceDelete'])->name('bulk-force-delete');
            Route::patch('/{unit}/toggle-status', [AdminUnitController::class, 'toggleStatus'])->name('toggle-status');

            Route::prefix('{unit}/photos')->name('photos.')->group(function () {
                Route::get('/', [AdminUnitPhotoController::class, 'index'])->name('index');
                Route::post('/upload', [AdminUnitPhotoController::class, 'upload'])->name('upload');
                Route::delete('/{photo}', [AdminUnitPhotoController::class, 'destroy'])->name('destroy');
                Route::post('/{photo}/primary', [AdminUnitPhotoController::class, 'setPrimary'])->name('set-primary');
                Route::post('/reorder', [AdminUnitPhotoController::class, 'reorder'])->name('reorder');
            });

            Route::post('/{unit}/facilities/sync', [AdminUnitController::class, 'syncFacilities'])->name('sync-facilities');
        });

        Route::resource('rating-categories', AdminRatingCategoryController::class);
        Route::patch('/rating-categories/{category}/toggle-active', [AdminRatingCategoryController::class, 'toggleActive'])->name('rating-categories.toggle-active');
        Route::post('/rating-categories/reorder', [AdminRatingCategoryController::class, 'reorder'])->name('rating-categories.reorder');
        Route::get('/rating-categories/active/list', [AdminRatingCategoryController::class, 'getActive'])->name('rating-categories.active');
        Route::get('/rating-categories/stats/data', [AdminRatingCategoryController::class, 'stats'])->name('rating-categories.stats');
        Route::post('/rating-categories/seed-defaults', [AdminRatingCategoryController::class, 'seedDefaults'])->name('rating-categories.seed-defaults');
        Route::post('/rating-categories/validate-for-rating', [AdminRatingCategoryController::class, 'validateForRating'])->name('rating-categories.validate');
        Route::get('/rating-categories/export/data', [AdminRatingCategoryController::class, 'export'])->name('rating-categories.export');

        Route::prefix('ratings')->name('ratings.')->group(function () {
            Route::get('/', [AdminRatingController::class, 'index'])->name('index');
            Route::get('/stats/overview', [AdminRatingController::class, 'stats'])->name('stats');
            Route::get('/unit/{unitId}/stats', [AdminRatingController::class, 'unitStats'])->name('unit-stats');
            Route::get('/export/data', [AdminRatingController::class, 'export'])->name('export');
            Route::get('/{rating}', [AdminRatingController::class, 'show'])->name('show');
            Route::patch('/{rating}/status', [AdminRatingController::class, 'updateStatus'])->name('update-status');
            Route::post('/{rating}/moderate', [AdminRatingController::class, 'moderate'])->name('moderate');
            Route::post('/bulk-action', [AdminRatingController::class, 'bulkAction'])->name('bulk-action');
            Route::delete('/{rating}', [AdminRatingController::class, 'destroy'])->name('destroy');
        });

        Route::resource('report-categories', AdminReportCategoryController::class);
        Route::patch('/report-categories/{category}/toggle-status', [AdminReportCategoryController::class, 'toggleStatus'])->name('report-categories.toggle-status');

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [AdminReportController::class, 'index'])->name('index');
            Route::get('/stats/overview', [AdminReportController::class, 'stats'])->name('stats');
            Route::get('/unit/{unitId}/stats', [AdminReportController::class, 'unitStats'])->name('unit-stats');
            Route::get('/export/data', [AdminReportController::class, 'export'])->name('export');
            Route::get('/{report}', [AdminReportController::class, 'show'])->name('show');
            Route::get('/{report}/edit', [AdminReportController::class, 'edit'])->name('edit');
            Route::put('/{report}', [AdminReportController::class, 'update'])->name('update');
            Route::put('/{report}/status', [AdminReportController::class, 'updateStatus'])->name('update-status');
            Route::post('/{report}/reply', [AdminReportController::class, 'reply'])->name('reply');
            Route::post('/bulk-action', [AdminReportController::class, 'bulkAction'])->name('bulk-action');
            Route::delete('/{report}', [AdminReportController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/', [AdminEmployeeController::class, 'index'])->name('index');
            Route::get('/create', [AdminEmployeeController::class, 'create'])->name('create');
            Route::post('/', [AdminEmployeeController::class, 'store'])->name('store');
            Route::get('/{id}', [AdminEmployeeController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [AdminEmployeeController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminEmployeeController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminEmployeeController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/restore', [AdminEmployeeController::class, 'restore'])->name('restore');
            Route::post('/assign', [AdminEmployeeController::class, 'assignToUnit'])->name('assign');
            Route::delete('/{employeeId}/unit/{unitId}', [AdminEmployeeController::class, 'removeFromUnit'])->name('remove-unit');
        });

        Route::prefix('qr-codes')->name('qr-codes.')->group(function () {
            Route::get('/', [AdminQrCodeController::class, 'index'])->name('index');
            Route::get('/unit/{unitId}', [AdminQrCodeController::class, 'show'])->name('show');
            Route::post('/generate', [AdminQrCodeController::class, 'generate'])->name('generate');
            Route::put('/{id}/regenerate', [AdminQrCodeController::class, 'regenerate'])->name('regenerate');
            Route::put('/{id}/toggle', [AdminQrCodeController::class, 'toggleActive'])->name('toggle');
            Route::delete('/{id}', [AdminQrCodeController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('moderation-logs')->name('moderation-logs.')->group(function () {
            Route::get('/', [AdminModerationLogController::class, 'index'])->name('index');
            Route::get('/stats/overview', [AdminModerationLogController::class, 'stats'])->name('stats');
            Route::get('/by-target/{targetType}/{targetId}', [AdminModerationLogController::class, 'byTarget'])->name('by-target');
            Route::get('/by-admin/{adminId}', [AdminModerationLogController::class, 'byAdmin'])->name('by-admin');
            Route::get('/summary', [AdminModerationLogController::class, 'summary'])->name('summary');
            Route::get('/export/data', [AdminModerationLogController::class, 'export'])->name('export');
            Route::get('/{log}', [AdminModerationLogController::class, 'show'])->name('show');
            Route::post('/cleanup', [AdminModerationLogController::class, 'cleanup'])->name('cleanup');
            Route::delete('/{log}', [AdminModerationLogController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-destroy', [AdminModerationLogController::class, 'bulkDestroy'])->name('bulk-destroy');
        });

        Route::prefix('exports')->name('exports.')->group(function () {
            Route::get('/', [AdminExportController::class, 'index'])->name('index');
            Route::post('/reports', [AdminExportController::class, 'exportReports'])->name('reports');
            Route::post('/ratings', [AdminExportController::class, 'exportRatings'])->name('ratings');
            Route::post('/units', [AdminExportController::class, 'exportUnits'])->name('units');
            Route::post('/unit-types', [AdminExportController::class, 'exportUnitTypes'])->name('unit-types');
            Route::get('/download/{id}', [AdminExportController::class, 'downloadExport'])->name('download');
        });

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [AdminSettingController::class, 'index'])->name('index');
            Route::put('/', [AdminSettingController::class, 'update'])->name('update');
        });
    });
});