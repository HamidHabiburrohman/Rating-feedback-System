<?php

use App\Http\Controllers\Employee\Auth\ForgotPasswordController;
use App\Http\Controllers\Employee\Auth\LoginController;
use App\Http\Controllers\Employee\Auth\ResetPasswordController;
use App\Http\Controllers\Employee\Conversation\ConversationController;
use App\Http\Controllers\Employee\Conversation\MessageAttachmentController;
use App\Http\Controllers\Employee\Conversation\MessageController;
use App\Http\Controllers\Employee\Conversation\MessageReadController;
use App\Http\Controllers\Employee\Dashboard\DashboardController;
use App\Http\Controllers\Employee\Profile\ProfileController;
use App\Http\Controllers\Employee\Rating\RatingController;
use App\Http\Controllers\Employee\Report\ReportController;
use App\Http\Controllers\Employee\Unit\UnitController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::middleware(['employee'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('check-auth', [LoginController::class, 'check'])->name('auth.check');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('stats', [DashboardController::class, 'stats'])->name('stats');
        Route::get('recent-ratings', [DashboardController::class, 'recentRatings'])->name('recent-ratings');
        Route::get('recent-reports', [DashboardController::class, 'recentReports'])->name('recent-reports');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('update', [ProfileController::class, 'update'])->name('update');
        Route::put('update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
        Route::post('update-photo', [ProfileController::class, 'updatePhoto'])->name('update-photo');
        Route::delete('remove-photo', [ProfileController::class, 'removePhoto'])->name('remove-photo');
        Route::put('update-preferences', [ProfileController::class, 'updatePreferences'])->name('update-preferences');
    });

    Route::prefix('units')->name('units.')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->name('index');
        Route::get('{id}', [UnitController::class, 'show'])->name('show');
        Route::get('{id}/edit', [UnitController::class, 'edit'])->name('edit');
        Route::put('{id}', [UnitController::class, 'update'])->name('update');
        Route::get('{unitId}/rating-categories', [UnitController::class, 'ratingCategories'])->name('rating-categories');
    });

    Route::prefix('ratings')->name('ratings.')->group(function () {
        Route::get('/', [RatingController::class, 'index'])->name('index');
        Route::get('{ratingId}/replies', [RatingController::class, 'getReplies'])->name('replies');
        Route::get('{id}', [RatingController::class, 'show'])->name('show');
        Route::post('{ratingId}/reply', [RatingController::class, 'storeReply'])->name('reply.store');
        Route::put('reply/{replyId}', [RatingController::class, 'updateReply'])->name('reply.update');
        Route::delete('reply/{replyId}', [RatingController::class, 'destroyReply'])->name('reply.destroy');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('{id}/history', [ReportController::class, 'history'])->name('history');
        Route::get('{id}', [ReportController::class, 'show'])->name('show');
        Route::post('{id}/update-status', [ReportController::class, 'updateStatus'])->name('update-status');
        Route::post('{id}/resolve', [ReportController::class, 'resolve'])->name('resolve');
        Route::post('{id}/reopen', [ReportController::class, 'reopen'])->name('reopen');
        Route::post('{reportId}/reply', [ReportController::class, 'storeReply'])->name('reply.store');
        Route::put('reply/{replyId}', [ReportController::class, 'updateReply'])->name('reply.update');
        Route::delete('reply/{replyId}', [ReportController::class, 'destroyReply'])->name('reply.destroy');
    });

    Route::prefix('conversations')->name('conversations.')->group(function () {
        Route::get('/', [ConversationController::class, 'index'])->name('index');
        Route::get('unread-count', [ConversationController::class, 'unreadCount'])->name('unread-count');
        Route::get('/{conversation}', [ConversationController::class, 'show'])->name('show');
        Route::post('/{conversation}/read-all', [MessageReadController::class, 'markAllAsRead'])->name('read-all');

        Route::prefix('{conversation}/messages')->name('messages.')->group(function () {
            Route::get('/', [MessageController::class, 'index'])->name('index');
            Route::post('/', [MessageController::class, 'store'])->name('store');
            Route::put('/{message}', [MessageController::class, 'update'])->name('update');
            Route::delete('/{message}', [MessageController::class, 'destroy'])->name('destroy');
            
            Route::post('/{message}/attachments', [MessageAttachmentController::class, 'upload'])->name('attachments.upload');
            Route::post('/{message}/read', [MessageReadController::class, 'markAsRead'])->name('read');
        });
    });

    Route::get('attachments/{attachment}/download', [MessageAttachmentController::class, 'download'])->name('attachments.download');
});