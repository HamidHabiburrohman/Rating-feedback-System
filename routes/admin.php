<?php

use Illuminate\Support\Facades\Route;

// Auth Controllers
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;

// Feature Controllers
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Profile\ProfileController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Unit\UnitController;
use App\Http\Controllers\Admin\Unit\UnitTypeController;
use App\Http\Controllers\Admin\Unit\UnitDepartmentController;
use App\Http\Controllers\Admin\Unit\UnitPhotoController;
use App\Http\Controllers\Admin\Unit\FacilityController;
use App\Http\Controllers\Admin\Employee\EmployeeController;
use App\Http\Controllers\Admin\QRCode\QrCodeController;
use App\Http\Controllers\Admin\Rating\RatingCategoryController;
use App\Http\Controllers\Admin\Rating\RatingController;
use App\Http\Controllers\Admin\Report\ReportCategoryController;
use App\Http\Controllers\Admin\Report\ReportController;
use App\Http\Controllers\Admin\Report\ExportController;
use App\Http\Controllers\Admin\Setting\SettingController;
use App\Http\Controllers\Admin\Moderation\ModerationLogController;
use App\Http\Controllers\Admin\Notification\NotificationController;

// Conversation Controllers
use App\Http\Controllers\Admin\Conversation\ConversationController;
use App\Http\Controllers\Admin\Conversation\ConversationParticipantController;
use App\Http\Controllers\Admin\Conversation\MessageController;
use App\Http\Controllers\Admin\Conversation\MessageAttachmentController;
use App\Http\Controllers\Admin\Conversation\MessageReadController;

/*
|--------------------------------------------------------------------------
| Guest / Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['admin', 'role:super_admin,admin'])->group(function () {

    // Auth Session Actions
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('check-auth', [LoginController::class, 'check'])->name('auth.check');

    // -----------------------------------------------------------------
    // DASHBOARD
    // -----------------------------------------------------------------
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/stats', [DashboardController::class, 'stats'])->name('stats');
        Route::get('/charts', [DashboardController::class, 'charts'])->name('charts');
        Route::get('/overview', [DashboardController::class, 'overview'])->name('overview');
        Route::get('/audit-logs', [DashboardController::class, 'auditLogs'])->name('audit-logs');
        Route::get('/recent-rated', [DashboardController::class, 'recentRated'])->name('recent-rated');
        Route::get('/top-units/{type?}', [DashboardController::class, 'topUnits'])->name('top-units');
        Route::get('/attention-units', [DashboardController::class, 'attentionUnits'])->name('attention-units');
        Route::get('/top-employees', [DashboardController::class, 'topEmployees'])->name('top-employees');
    });

    // -----------------------------------------------------------------
    // PROFILE MANAGEMENT (Logged-in User Self Profile)
    // -----------------------------------------------------------------
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'updateProfile'])->name('update');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
        Route::post('/update-photo', [ProfileController::class, 'updatePhoto'])->name('update-photo');
        Route::delete('/remove-photo', [ProfileController::class, 'removePhoto'])->name('remove-photo');
        Route::put('/update-preferences', [ProfileController::class, 'updatePreferences'])->name('update-preferences');
    });

    // -----------------------------------------------------------------
    // USER / OTHER ADMIN MANAGEMENT (CRUD Other Admin Accounts)
    // -----------------------------------------------------------------
    Route::resource('users', UserController::class);
    Route::post('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // -----------------------------------------------------------------
    // UNITS DOMAIN (Units, Types, Departments, Facilities, Photos)
    // -----------------------------------------------------------------
    Route::get('units/trashed', [UnitController::class, 'trashed'])->name('units.trashed');
    Route::post('units/{id}/restore', [UnitController::class, 'restore'])->name('units.restore');
    Route::delete('units/{id}/force-delete', [UnitController::class, 'forceDelete'])->name('units.force-delete');
    Route::post('units/{id}/toggle-status', [UnitController::class, 'toggleStatus'])->name('units.toggle-status');
    Route::post('units/{id}/sync-facilities', [UnitController::class, 'syncFacilities'])->name('units.sync-facilities');
    Route::post('units/bulk-delete', [UnitController::class, 'bulkDelete'])->name('units.bulk-delete');
    Route::post('units/bulk-restore', [UnitController::class, 'bulkRestore'])->name('units.bulk-restore');
    Route::post('units/bulk-force-delete', [UnitController::class, 'bulkForceDelete'])->name('units.force-delete-bulk');
    Route::post('units/bulk-activate', [UnitController::class, 'bulkActivate'])->name('units.bulk-activate');
    Route::resource('units', UnitController::class);

    // Unit Sub-modules
    Route::resource('unit-types', UnitTypeController::class);
    Route::post('unit-types/reorder', [UnitTypeController::class, 'reorder'])->name('unit-types.reorder');

    Route::resource('unit-departments', UnitDepartmentController::class);
    Route::post('unit-departments/{id}/toggle-status', [UnitDepartmentController::class, 'toggleStatus'])->name('unit-departments.toggle-status');
    Route::get('unit-departments/stats', [UnitDepartmentController::class, 'stats'])->name('unit-departments.stats');

    Route::get('facilities/popular', [FacilityController::class, 'popular'])->name('facilities.popular');
    Route::resource('facilities', FacilityController::class);

    // Unit Photos & Unit-Specific QR Codes
    Route::prefix('units/{unit}')->name('units.')->group(function () {
        Route::post('/photos/upload', [UnitPhotoController::class, 'upload'])->name('photos.upload');
        Route::post('/photos/{photoId}/primary', [UnitPhotoController::class, 'setPrimary'])->name('photos.set-primary');
        Route::delete('/photos/{photoId}', [UnitPhotoController::class, 'destroy'])->name('photos.destroy');

        Route::get('qr-codes', [QrCodeController::class, 'index'])->name('qr-codes.index');
        Route::post('qr-codes/generate', [QrCodeController::class, 'generate'])->name('qr-codes.generate');
    });

    // -----------------------------------------------------------------
    // GLOBAL QR CODES MANAGEMENT
    // -----------------------------------------------------------------
    Route::prefix('qr-codes')->name('qr-codes.')->group(function () {
        Route::get('/', [QrCodeController::class, 'globalIndex'])->name('index');
        Route::post('/generate', [QrCodeController::class, 'generateGlobal'])->name('generate');
        Route::get('/search', [QrCodeController::class, 'search'])->name('search');
        Route::get('/stats', [QrCodeController::class, 'stats'])->name('stats');
        Route::get('{qrCode}/download', [QrCodeController::class, 'download'])->name('download');
        Route::get('{qrCode}/preview-data', [QrCodeController::class, 'previewData'])->name('preview-data');
        Route::get('{qrCode}/preview', [QrCodeController::class, 'preview'])->name('preview');
        Route::post('{qrCode}/regenerate', [QrCodeController::class, 'regenerate'])->name('regenerate');
        Route::patch('{qrCode}/activate', [QrCodeController::class, 'activate'])->name('activate');
        Route::patch('{qrCode}/deactivate', [QrCodeController::class, 'deactivate'])->name('deactivate');
        Route::delete('{qrCode}', [QrCodeController::class, 'destroy'])->name('destroy');
    });

    // -----------------------------------------------------------------
    // EMPLOYEES MANAGEMENT
    // -----------------------------------------------------------------
    Route::resource('employees', EmployeeController::class);
    Route::post('employees/{id}/restore', [EmployeeController::class, 'restore'])->name('employees.restore');
    Route::post('employees/{id}/assign-to-unit', [EmployeeController::class, 'assignToUnit'])->name('employees.assign-to-unit');
    Route::post('employees/{id}/remove-from-unit', [EmployeeController::class, 'removeFromUnit'])->name('employees.remove-from-unit');
    Route::get('employees/{id}/assigned-units', [EmployeeController::class, 'getAssignedUnits'])->name('employees.assigned-units');

    // -----------------------------------------------------------------
    // RATINGS MANAGEMENT
    // -----------------------------------------------------------------
    Route::get('rating-categories/active', [RatingCategoryController::class, 'active'])->name('rating-categories.active');
    Route::post('rating-categories/reorder', [RatingCategoryController::class, 'reorder'])->name('rating-categories.reorder');
    Route::post('rating-categories/{id}/toggle-active', [RatingCategoryController::class, 'toggleActive'])->name('rating-categories.toggle-active');
    Route::resource('rating-categories', RatingCategoryController::class)->except(['show']);

    Route::get('ratings', [RatingController::class, 'index'])->name('ratings.index');
    Route::get('ratings/stats', [RatingController::class, 'stats'])->name('ratings.stats');
    Route::get('ratings/export', [RatingController::class, 'export'])->name('ratings.export');
    Route::get('ratings/{id}', [RatingController::class, 'show'])->name('ratings.show');
    Route::post('ratings/{id}/moderate', [RatingController::class, 'moderate'])->name('ratings.moderate');
    Route::post('ratings/{id}/update-status', [RatingController::class, 'updateStatus'])->name('ratings.update-status');
    Route::post('ratings/bulk-action', [RatingController::class, 'bulkAction'])->name('ratings.bulk-action');
    Route::delete('ratings/{id}', [RatingController::class, 'destroy'])->name('ratings.destroy');

    // -----------------------------------------------------------------
    // REPORTS & EXPORTS DOMAIN
    // -----------------------------------------------------------------
    Route::post('report-categories/{id}/toggle-active', [ReportCategoryController::class, 'toggleActive'])->name('report-categories.toggle-active');
    Route::resource('report-categories', ReportCategoryController::class)->except(['show']);

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    Route::delete('reports/{id}', [ReportController::class, 'destroy'])->name('reports.destroy');

    // Export Actions
    Route::prefix('exports')->name('exports.')->group(function () {
        Route::get('/', [ExportController::class, 'index'])->name('index');
        Route::get('/{id}/download', [ExportController::class, 'downloadExport'])->name('download');
        Route::post('/reports', [ExportController::class, 'exportReports'])->name('reports');
        Route::post('/ratings', [ExportController::class, 'exportRatings'])->name('ratings');
        Route::post('/units', [ExportController::class, 'exportUnits'])->name('units');
        Route::post('/unit-types', [ExportController::class, 'exportUnitTypes'])->name('unit-types');
    });

    // -----------------------------------------------------------------
    // CONVERSATIONS MODULE
    // -----------------------------------------------------------------
    Route::prefix('conversations')->name('conversations.')->group(function () {
        Route::get('/', [ConversationController::class, 'index'])->name('index');
        Route::get('/unread-count', [ConversationController::class, 'unreadCount'])->name('unread-count');
        Route::post('/', [ConversationController::class, 'store'])->name('store');
        Route::get('/{conversation}', [ConversationController::class, 'show'])->name('show');
        Route::put('/{conversation}', [ConversationController::class, 'update'])->name('update');
        Route::delete('/{conversation}', [ConversationController::class, 'destroy'])->name('destroy');

        Route::patch('/{conversation}/archive', [ConversationController::class, 'archive'])->name('archive');
        Route::patch('/{conversation}/reopen', [ConversationController::class, 'reopen'])->name('reopen');
        Route::patch('/{conversation}/close', [ConversationController::class, 'close'])->name('close');

        // Participants
        Route::prefix('{conversation}/participants')->name('participants.')->group(function () {
            Route::get('/', [ConversationParticipantController::class, 'index'])->name('index');
            Route::post('/', [ConversationParticipantController::class, 'store'])->name('store');
            Route::delete('/{participant}', [ConversationParticipantController::class, 'destroy'])->name('destroy');
            Route::post('/{participant}/leave', [ConversationParticipantController::class, 'leave'])->name('leave');
        });

        // Messages
        Route::prefix('{conversation}/messages')->name('messages.')->group(function () {
            Route::get('/', [MessageController::class, 'index'])->name('index');
            Route::post('/', [MessageController::class, 'store'])->name('store');
            Route::get('/{message}', [MessageController::class, 'show'])->name('show');
            Route::put('/{message}', [MessageController::class, 'update'])->name('update');
            Route::delete('/{message}', [MessageController::class, 'destroy'])->name('destroy');

            Route::patch('/{message}/read', [MessageReadController::class, 'markAsRead'])->name('read');
            Route::post('/read-all', [MessageReadController::class, 'markAllAsRead'])->name('read-all');

            Route::post('/{message}/attachments', [MessageAttachmentController::class, 'upload'])->name('attachments.upload');
            Route::get('/{message}/attachments/{attachment}', [MessageAttachmentController::class, 'download'])->name('attachments.download');
            Route::delete('/{message}/attachments/{attachment}', [MessageAttachmentController::class, 'destroy'])->name('attachments.destroy');
        });
    });

    // Direct Attachment Download Route
    Route::get('attachments/{attachment}/download', [MessageAttachmentController::class, 'downloadDirect'])->name('attachments.download-direct');

    // -----------------------------------------------------------------
    // MODERATION LOGS
    // -----------------------------------------------------------------
    Route::prefix('moderation-logs')->name('moderation-logs.')->group(function () {
        Route::get('/', [ModerationLogController::class, 'index'])->name('index');
        Route::get('/stats', [ModerationLogController::class, 'stats'])->name('stats');
        Route::get('/export', [ModerationLogController::class, 'export'])->name('export');
        Route::get('/summary', [ModerationLogController::class, 'summary'])->name('summary');
        Route::get('/by-target/{targetType}/{targetId}', [ModerationLogController::class, 'byTarget'])->name('by-target');
        Route::get('/by-admin/{adminId}', [ModerationLogController::class, 'byAdmin'])->name('by-admin');
        Route::get('/{id}', [ModerationLogController::class, 'show'])->name('show');
        Route::post('/cleanup', [ModerationLogController::class, 'cleanup'])->name('cleanup');
        Route::post('/bulk-destroy', [ModerationLogController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::delete('/{log}', [ModerationLogController::class, 'destroy'])->name('destroy');
    });

    // -----------------------------------------------------------------
    // SETTINGS & NOTIFICATIONS
    // -----------------------------------------------------------------
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/latest', [NotificationController::class, 'latest'])->name('notifications.latest');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});
