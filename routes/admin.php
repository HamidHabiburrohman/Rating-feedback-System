<?php

use App\Http\Controllers\Admin\Export\ExportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\Conversation\ConversationController;
use App\Http\Controllers\Admin\Conversation\ConversationParticipantController;
use App\Http\Controllers\Admin\Conversation\MessageController;
use App\Http\Controllers\Admin\Conversation\MessageAttachmentController;
use App\Http\Controllers\Admin\Conversation\MessageReadController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Profile\ProfileController;
use App\Http\Controllers\Admin\Unit\UnitController;
use App\Http\Controllers\Admin\Unit\UnitTypeController;
use App\Http\Controllers\Admin\Unit\UnitDepartmentController;
use App\Http\Controllers\Admin\Unit\UnitPhotoController;
use App\Http\Controllers\Admin\Facility\FacilityController;
use App\Http\Controllers\Admin\Employee\EmployeeController;
use App\Http\Controllers\Admin\QRCode\QrCodeController;
use App\Http\Controllers\Admin\Rating\RatingCategoryController;
use App\Http\Controllers\Admin\Rating\RatingController;
use App\Http\Controllers\Admin\Report\ReportCategoryController;
use App\Http\Controllers\Admin\Report\ReportController;
use App\Http\Controllers\Admin\Setting\SettingController;
use App\Http\Controllers\Admin\Moderation\ModerationLogController;
use App\Http\Controllers\Admin\Notification\NotificationController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware(['admin', 'role:super_admin,admin'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('check-auth', [LoginController::class, 'check'])->name('auth.check');

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
        Route::get('/charts', [DashboardController::class, 'charts'])->name('dashboard.charts');
        Route::get('/overview', [DashboardController::class, 'overview'])->name('dashboard.overview');
        Route::get('/audit-logs', [DashboardController::class, 'auditLogs'])->name('dashboard.audit-logs');
        Route::get('/recent-rated', [DashboardController::class, 'recentRated'])->name('dashboard.recent-rated');
        Route::get('/top-units/{type?}', [DashboardController::class, 'topUnits'])->name('dashboard.top-units');
        Route::get('/attention-units', [DashboardController::class, 'attentionUnits'])->name('dashboard.attention-units');
        Route::get('/top-employees', [DashboardController::class, 'topEmployees'])->name('dashboard.top-employees');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
        Route::post('/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
        Route::delete('/remove-photo', [ProfileController::class, 'removePhoto'])->name('profile.remove-photo');
        Route::put('/update-preferences', [ProfileController::class, 'updatePreferences'])->name('profile.update-preferences');
    });

    Route::resource('units', UnitController::class);
    Route::get('units/trashed', [UnitController::class, 'trashed'])->name('units.trashed');
    Route::post('units/{id}/restore', [UnitController::class, 'restore'])->name('units.restore');
    Route::delete('units/{id}/force-delete', [UnitController::class, 'forceDelete'])->name('units.force-delete');
    Route::post('units/{id}/toggle-status', [UnitController::class, 'toggleStatus'])->name('units.toggle-status');
    Route::post('units/{id}/sync-facilities', [UnitController::class, 'syncFacilities'])->name('units.sync-facilities');
    Route::post('units/bulk-delete', [UnitController::class, 'bulkDelete'])->name('units.bulk-delete');
    Route::post('units/bulk-restore', [UnitController::class, 'bulkRestore'])->name('units.bulk-restore');
    Route::post('units/bulk-force-delete', [UnitController::class, 'bulkForceDelete'])->name('units.force-delete-bulk');
    Route::post('units/bulk-activate', [UnitController::class, 'bulkActivate'])->name('units.bulk-activate');

    // -----------------------------------------------------------------
    // QR CODES MANAGEMENT
    // -----------------------------------------------------------------
    Route::prefix('units/{unit}')->group(function () {
        Route::post('/upload', [UnitPhotoController::class, 'upload'])->name('admin.units.photos.upload');
        Route::post('/{photoId}/primary', [UnitPhotoController::class, 'setPrimary'])->name('admin.units.photos.set-primary');
        Route::delete('/{photoId}', [UnitPhotoController::class, 'destroy'])->name('admin.units.photos.destroy');
        Route::get('qr-codes', [QrCodeController::class, 'index'])->name('units.qr-codes.index');
        Route::post('qr-codes/generate', [QrCodeController::class, 'generate'])->name('units.qr-codes.generate');
    });

    Route::prefix('qr-codes')->group(function () {
        Route::get('/', [QrCodeController::class, 'globalIndex'])->name('qr-codes.index');
        Route::post('/generate', [QrCodeController::class, 'generateGlobal'])->name('qr-codes.generate');
        Route::get('/search', [QrCodeController::class, 'search'])->name('qr-codes.search');
        Route::get('/stats', [QrCodeController::class, 'stats'])->name('qr-codes.stats');
        Route::get('{qrCode}/download', [QrCodeController::class, 'download'])->name('qr-codes.download');
        Route::get('{qrCode}/preview-data', [QrCodeController::class, 'previewData'])->name('qr-codes.preview-data');
        Route::get('{qrCode}/preview', [QrCodeController::class, 'preview'])->name('qr-codes.preview');
        Route::post('{qrCode}/regenerate', [QrCodeController::class, 'regenerate'])->name('qr-codes.regenerate');
        Route::patch('{qrCode}/activate', [QrCodeController::class, 'activate'])->name('qr-codes.activate');
        Route::patch('{qrCode}/deactivate', [QrCodeController::class, 'deactivate'])->name('qr-codes.deactivate');
        Route::delete('{qrCode}', [QrCodeController::class, 'destroy'])->name('qr-codes.destroy');
    });

    // -----------------------------------------------------------------
    // CONVERSATIONS MODULE
    // Independent conversation system between Admin and Employee
    // -----------------------------------------------------------------
    Route::prefix('conversations')->name('conversations.')->group(function () {

        // Conversation Management
        Route::get('/', [ConversationController::class, 'index'])->name('index');
        Route::get('/unread-count', [ConversationController::class, 'unreadCount'])->name('unread-count');
        Route::post('/', [ConversationController::class, 'store'])->name('store');
        Route::get('/{conversation}', [ConversationController::class, 'show'])->name('show');
        Route::put('/{conversation}', [ConversationController::class, 'update'])->name('update');
        Route::delete('/{conversation}', [ConversationController::class, 'destroy'])->name('destroy');

        // Conversation Actions
        Route::patch('/{conversation}/archive', [ConversationController::class, 'archive'])->name('archive');
        Route::patch('/{conversation}/reopen', [ConversationController::class, 'reopen'])->name('reopen');
        Route::patch('/{conversation}/close', [ConversationController::class, 'close'])->name('close');

        // Conversation Participants
        Route::prefix('{conversation}/participants')->name('participants.')->group(function () {
            Route::get('/', [ConversationParticipantController::class, 'index'])->name('index');
            Route::post('/', [ConversationParticipantController::class, 'store'])->name('store');
            Route::delete('/{participant}', [ConversationParticipantController::class, 'destroy'])->name('destroy');
            Route::post('/{participant}/leave', [ConversationParticipantController::class, 'leave'])->name('leave');
        });

        // Messages (nested under conversation)
        Route::prefix('{conversation}/messages')->name('messages.')->group(function () {
            Route::get('/', [MessageController::class, 'index'])->name('index');
            Route::post('/', [MessageController::class, 'store'])->name('store');
            Route::get('/{message}', [MessageController::class, 'show'])->name('show');
            Route::put('/{message}', [MessageController::class, 'update'])->name('update');
            Route::delete('/{message}', [MessageController::class, 'destroy'])->name('destroy');
            Route::post('/read-all', [MessageReadController::class, 'markAllAsRead'])->name('read-all');

            // Message Actions
            Route::patch('/{message}/read', [MessageReadController::class, 'markAsRead'])->name('read');
            Route::post('/read-all', [MessageReadController::class, 'markAllAsRead'])->name('read-all');

            // Message Attachments
            Route::post('/{message}/attachments', [MessageAttachmentController::class, 'upload'])->name('attachments.upload');
            Route::get('/{message}/attachments/{attachment}', [MessageAttachmentController::class, 'download'])->name('attachments.download');
            Route::delete('/{message}/attachments/{attachment}', [MessageAttachmentController::class, 'destroy'])->name('attachments.destroy');
            Route::get('attachments/{attachment}/download', [MessageAttachmentController::class, 'downloadDirect'])->name('attachments.download-direct');
        });
    });

    // Global attachment download (direct access without conversation context)
    Route::get('attachments/{attachment}/download', [MessageAttachmentController::class, 'downloadDirect'])->name('attachments.download-direct');

    // Global attachment download (not tied to specific conversation UI)
    Route::get('attachments/{attachment}/download', [MessageAttachmentController::class, 'download'])->name('attachments.download');

    Route::resource('unit-types', UnitTypeController::class);
    Route::post('unit-types/reorder', [UnitTypeController::class, 'reorder'])->name('unit-types.reorder');

    Route::resource('unit-departments', UnitDepartmentController::class);
    Route::post('unit-departments/{id}/toggle-status', [UnitDepartmentController::class, 'toggleStatus'])->name('unit-departments.toggle-status');
    Route::get('unit-departments/stats', [UnitDepartmentController::class, 'stats'])->name('unit-departments.stats');

    Route::resource('facilities', FacilityController::class);
    Route::get('facilities/popular', [FacilityController::class, 'popular'])->name('facilities.popular');

    Route::resource('employees', EmployeeController::class);
    Route::post('employees/{id}/restore', [EmployeeController::class, 'restore'])->name('employees.restore');
    Route::post('employees/{id}/assign-to-unit', [EmployeeController::class, 'assignToUnit'])->name('employees.assign-to-unit');
    Route::post('employees/{id}/remove-from-unit', [EmployeeController::class, 'removeFromUnit'])->name('employees.remove-from-unit');
    Route::get('employees/{id}/assigned-units', [EmployeeController::class, 'getAssignedUnits'])->name('employees.assigned-units');

    Route::resource('rating-categories', RatingCategoryController::class)->except(['show']);
    Route::post('rating-categories/{id}/toggle-active', [RatingCategoryController::class, 'toggleActive'])->name('rating-categories.toggle-active');
    Route::post('rating-categories/reorder', [RatingCategoryController::class, 'reorder'])->name('rating-categories.reorder');
    Route::get('rating-categories/active', [RatingCategoryController::class, 'active'])->name('rating-categories.active');

    Route::get('ratings', [RatingController::class, 'index'])->name('ratings.index');
    Route::get('ratings/stats', [RatingController::class, 'stats'])->name('ratings.stats');
    Route::get('ratings/export', [RatingController::class, 'export'])->name('ratings.export');
    Route::get('ratings/{id}', [RatingController::class, 'show'])->name('ratings.show');
    Route::post('ratings/{id}/moderate', [RatingController::class, 'moderate'])->name('ratings.moderate');
    Route::post('ratings/{id}/update-status', [RatingController::class, 'updateStatus'])->name('ratings.update-status');
    Route::post('ratings/bulk-action', [RatingController::class, 'bulkAction'])->name('ratings.bulk-action');
    Route::delete('ratings/{id}', [RatingController::class, 'destroy'])->name('ratings.destroy');

    Route::resource('report-categories', ReportCategoryController::class)->except(['show']);
    Route::post('report-categories/{id}/toggle-active', [ReportCategoryController::class, 'toggleActive'])->name('report-categories.toggle-active');

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    Route::delete('reports/{id}', [ReportController::class, 'destroy'])->name('reports.destroy');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::prefix('moderation-logs')->group(function () {
        Route::get('moderation-logs', [ModerationLogController::class, 'index'])->name('moderation-logs.index');
        Route::get('moderation-logs/stats', [ModerationLogController::class, 'stats'])->name('moderation-logs.stats');
        Route::get('moderation-logs/export', [ModerationLogController::class, 'export'])->name('moderation-logs.export');
        Route::get('moderation-logs/summary', [ModerationLogController::class, 'summary'])->name('moderation-logs.summary');
        Route::get('moderation-logs/by-target/{targetType}/{targetId}', [ModerationLogController::class, 'byTarget'])->name('moderation-logs.by-target');
        Route::get('moderation-logs/by-admin/{adminId}', [ModerationLogController::class, 'byAdmin'])->name('moderation-logs.by-admin');
        Route::get('moderation-logs/{id}', [ModerationLogController::class, 'show'])->name('moderation-logs.show');
        Route::post('moderation-logs/cleanup', [ModerationLogController::class, 'cleanup'])->name('moderation-logs.cleanup');
        Route::post('moderation-logs/bulk-destroy', [ModerationLogController::class, 'bulkDestroy'])->name('moderation-logs.bulk-destroy');
        Route::delete('moderation-logs/{log}', [ModerationLogController::class, 'destroy'])->name('moderation-logs.destroy');
    });

    Route::get('exports', [ExportController::class, 'index'])->name('exports.index');
    Route::get('exports/{id}/download', [ExportController::class, 'downloadExport'])->name('exports.download');
    Route::post('exports/reports', [ExportController::class, 'exportReports'])->name('exports.reports');
    Route::post('exports/ratings', [ExportController::class, 'exportRatings'])->name('exports.ratings');
    Route::post('exports/units', [ExportController::class, 'exportUnits'])->name('exports.units');
    Route::post('exports/unit-types', [ExportController::class, 'exportUnitTypes'])->name('exports.unit-types');

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/latest', [NotificationController::class, 'latest'])->name('notifications.latest');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});
