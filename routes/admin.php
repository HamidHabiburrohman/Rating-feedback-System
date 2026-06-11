<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UnitTypeController;
use App\Http\Controllers\Admin\UnitDepartmentController;
use App\Http\Controllers\Admin\UnitPhotoController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\RatingCategoryController;
use App\Http\Controllers\Admin\RatingController;
use App\Http\Controllers\Admin\ReportCategoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ModerationLogController;
use App\Http\Controllers\Admin\ExportController;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    
    Route::get('reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('check-auth', [LoginController::class, 'check'])->name('auth.check');

Route::middleware('role:super_admin,admin')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
    Route::get('dashboard/charts', [DashboardController::class, 'charts'])->name('dashboard.charts');
    Route::get('dashboard/overview', [DashboardController::class, 'overview'])->name('dashboard.overview');
    Route::get('dashboard/audit-logs', [DashboardController::class, 'auditLogs'])->name('dashboard.audit-logs');
    Route::get('dashboard/recent-rated', [DashboardController::class, 'recentRated'])->name('dashboard.recent-rated');
    Route::get('dashboard/top-units/{type?}', [DashboardController::class, 'topUnits'])->name('dashboard.top-units');
    Route::get('dashboard/attention-units', [DashboardController::class, 'attentionUnits'])->name('dashboard.attention-units');
    
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::post('profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
    Route::delete('profile/remove-photo', [ProfileController::class, 'removePhoto'])->name('profile.remove-photo');
    Route::put('profile/update-preferences', [ProfileController::class, 'updatePreferences'])->name('profile.update-preferences');
    
    Route::resource('units', UnitController::class);
    Route::get('units/trashed', [UnitController::class, 'trashed'])->name('units.trashed');
    Route::post('units/{id}/restore', [UnitController::class, 'restore'])->name('units.restore');
    Route::delete('units/{id}/force-delete', [UnitController::class, 'forceDelete'])->name('units.force-delete');
    Route::post('units/{id}/toggle-status', [UnitController::class, 'toggleStatus'])->name('units.toggle-status');
    Route::post('units/{id}/sync-facilities', [UnitController::class, 'syncFacilities'])->name('units.sync-facilities');
    Route::post('units/bulk-delete', [UnitController::class, 'bulkDelete'])->name('units.bulk-delete');
    Route::post('units/bulk-restore', [UnitController::class, 'bulkRestore'])->name('units.bulk-restore');
    Route::post('units/bulk-force-delete', [UnitController::class, 'bulkForceDelete'])->name('units.bulk-force-delete');
    Route::post('units/bulk-activate', [UnitController::class, 'bulkActivate'])->name('units.bulk-activate');
    
    Route::resource('unit-types', UnitTypeController::class);
    Route::post('unit-types/reorder', [UnitTypeController::class, 'reorder'])->name('unit-types.reorder');
    
    Route::resource('unit-departments', UnitDepartmentController::class);
    Route::post('unit-departments/{id}/toggle-status', [UnitDepartmentController::class, 'toggleStatus'])->name('unit-departments.toggle-status');
    Route::get('unit-departments/stats', [UnitDepartmentController::class, 'stats'])->name('unit-departments.stats');
    
    Route::prefix('units/{unit}')->group(function () {
        Route::get('photos', [UnitPhotoController::class, 'index'])->name('units.photos.index');
        Route::post('photos/upload', [UnitPhotoController::class, 'upload'])->name('units.photos.upload');
        Route::post('photos/{photo}/set-primary', [UnitPhotoController::class, 'setPrimary'])->name('units.photos.set-primary');
        Route::post('photos/reorder', [UnitPhotoController::class, 'reorder'])->name('units.photos.reorder');
        Route::delete('photos/{photo}', [UnitPhotoController::class, 'destroy'])->name('units.photos.destroy');
        
        Route::get('qr-codes', [QrCodeController::class, 'index'])->name('units.qr-codes.index');
        Route::post('qr-codes/generate', [QrCodeController::class, 'generate'])->name('units.qr-codes.generate');
    });
    
    Route::prefix('qr-codes')->group(function () {
        Route::post('{id}/regenerate', [QrCodeController::class, 'regenerate'])->name('qr-codes.regenerate');
        Route::post('{id}/toggle-active', [QrCodeController::class, 'toggleActive'])->name('qr-codes.toggle-active');
        Route::delete('{id}', [QrCodeController::class, 'destroy'])->name('qr-codes.destroy');
    });
    
    Route::resource('facilities', FacilityController::class);
    Route::get('facilities/popular', [FacilityController::class, 'popular'])->name('facilities.popular');
    
    Route::resource('employees', EmployeeController::class);
    Route::post('employees/{id}/restore', [EmployeeController::class, 'restore'])->name('employees.restore');
    Route::post('employees/{id}/assign-to-unit', [EmployeeController::class, 'assignToUnit'])->name('employees.assign-to-unit');
    Route::post('employees/{id}/remove-from-unit', [EmployeeController::class, 'removeFromUnit'])->name('employees.remove-from-unit');
    Route::get('employees/{id}/assigned-units', [EmployeeController::class, 'getAssignedUnits'])->name('employees.assigned-units');
    
    Route::resource('rating-categories', RatingCategoryController::class)->except(['create', 'show', 'edit']);
    Route::post('rating-categories/{id}/toggle-active', [RatingCategoryController::class, 'toggleActive'])->name('rating-categories.toggle-active');
    Route::post('rating-categories/reorder', [RatingCategoryController::class, 'reorder'])->name('rating-categories.reorder');
    Route::get('rating-categories/active', [RatingCategoryController::class, 'active'])->name('rating-categories.active');
    
    Route::get('ratings', [RatingController::class, 'index'])->name('ratings.index');
    Route::get('ratings/{id}', [RatingController::class, 'show'])->name('ratings.show');
    Route::post('ratings/{id}/moderate', [RatingController::class, 'moderate'])->name('ratings.moderate');
    Route::post('ratings/{id}/update-status', [RatingController::class, 'updateStatus'])->name('ratings.update-status');
    Route::post('ratings/bulk-action', [RatingController::class, 'bulkAction'])->name('ratings.bulk-action');
    Route::get('ratings/export', [RatingController::class, 'export'])->name('ratings.export');
    Route::get('ratings/stats', [RatingController::class, 'stats'])->name('ratings.stats');
    
    Route::resource('report-categories', ReportCategoryController::class)->except(['create', 'show', 'edit']);
    Route::post('report-categories/{id}/toggle-active', [ReportCategoryController::class, 'toggleActive'])->name('report-categories.toggle-active');
    
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    Route::post('reports/{id}/update-status', [ReportController::class, 'updateStatus'])->name('reports.update-status');
    Route::post('reports/{id}/reply', [ReportController::class, 'reply'])->name('reports.reply');
    Route::post('reports/bulk-update-status', [ReportController::class, 'bulkUpdateStatus'])->name('reports.bulk-update-status');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    
    Route::get('moderation-logs', [ModerationLogController::class, 'index'])->name('moderation-logs.index');
    Route::get('moderation-logs/{id}', [ModerationLogController::class, 'show'])->name('moderation-logs.show');
    Route::get('moderation-logs/stats', [ModerationLogController::class, 'stats'])->name('moderation-logs.stats');
    Route::get('moderation-logs/by-target/{targetType}/{targetId}', [ModerationLogController::class, 'byTarget'])->name('moderation-logs.by-target');
    Route::get('moderation-logs/export', [ModerationLogController::class, 'export'])->name('moderation-logs.export');
    Route::post('moderation-logs/cleanup', [ModerationLogController::class, 'cleanup'])->name('moderation-logs.cleanup');
    Route::get('moderation-logs/summary', [ModerationLogController::class, 'summary'])->name('moderation-logs.summary');
    Route::get('moderation-logs/by-admin/{adminId}', [ModerationLogController::class, 'byAdmin'])->name('moderation-logs.by-admin');
    Route::delete('moderation-logs/{log}', [ModerationLogController::class, 'destroy'])->name('moderation-logs.destroy');
    Route::post('moderation-logs/bulk-destroy', [ModerationLogController::class, 'bulkDestroy'])->name('moderation-logs.bulk-destroy');
    
    Route::get('exports', [ExportController::class, 'index'])->name('exports.index');
    Route::post('exports/reports', [ExportController::class, 'exportReports'])->name('exports.reports');
    Route::post('exports/ratings', [ExportController::class, 'exportRatings'])->name('exports.ratings');
    Route::post('exports/units', [ExportController::class, 'exportUnits'])->name('exports.units');
    Route::post('exports/unit-types', [ExportController::class, 'exportUnitTypes'])->name('exports.unit-types');
    Route::get('exports/{id}/download', [ExportController::class, 'downloadExport'])->name('exports.download');
});