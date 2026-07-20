<?php

use App\Http\Controllers\Student\Auth\ForgotPasswordController;
use App\Http\Controllers\Student\Auth\LoginController;
use App\Http\Controllers\Student\Auth\RegisterController;
use App\Http\Controllers\Student\Auth\ResetPasswordController;
use App\Http\Controllers\Student\Conversation\ConversationController;
use App\Http\Controllers\Student\Conversation\MessageAttachmentController;
use App\Http\Controllers\Student\Conversation\MessageController;
use App\Http\Controllers\Student\Conversation\MessageReadController;
use App\Http\Controllers\Student\Dashboard\DashboardController;
use App\Http\Controllers\Student\Notification\NotificationController;
use App\Http\Controllers\Student\Profile\ProfileController;
use App\Http\Controllers\Student\QRCode\QrCodeController;
use App\Http\Controllers\Student\Rating\RatingController;
use App\Http\Controllers\Student\Report\ReportController;
use App\Http\Controllers\Student\Unit\UnitController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
    
    Route::get('/verify-email/{id}', function (int $id) {
        if (!request()->hasValidSignature()) {
            abort(401, 'Link verifikasi tidak valid atau sudah kadaluarsa.');
        }
        $service = app(\App\Services\Student\Auth\AuthService::class);
        $service->verifyEmail($id);
        
        return redirect()->route('student.login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
    })->name('verify.email');
});

Route::middleware(['student'])->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('check-auth', [LoginController::class, 'check'])->name('auth.check');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('stats', [DashboardController::class, 'stats'])->name('stats');
        Route::get('activity-chart', [DashboardController::class, 'activityChart'])->name('activity-chart');
        Route::get('recommended-units', [DashboardController::class, 'recommendedUnits'])->name('recommended-units');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('update', [ProfileController::class, 'update'])->name('update');
        Route::put('update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
        Route::get('sessions', [ProfileController::class, 'sessions'])->name('sessions');
        Route::post('sessions/{sessionId}/terminate', [ProfileController::class, 'terminateSession'])->name('sessions.terminate');
        Route::post('sessions/terminate-all', [ProfileController::class, 'terminateAllSessions'])->name('sessions.terminate-all');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::post('{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        Route::delete('{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('units')->name('units.')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->name('index');
        Route::get('{slug}', [UnitController::class, 'show'])->name('show');
    });

    Route::get('units/{unit}/rate', [RatingController::class, 'create'])->name('ratings.create');

    Route::prefix('qr')->name('qr.')->group(function () {
        Route::get('scan', [QrCodeController::class, 'scanForm'])->name('scan');
        Route::get('result', [QrCodeController::class, 'scanResult'])->name('result');
        Route::post('validate', [QrCodeController::class, 'validateQr'])->name('validate');
        Route::get('check-status/{unitId}', [QrCodeController::class, 'checkStatus'])->name('check-status');
    });

    Route::prefix('ratings')->name('ratings.')->group(function () {
        Route::get('history', [RatingController::class, 'history'])->name('history');
        Route::post('/', [RatingController::class, 'store'])->name('store');
        Route::get('{trackingCode}', [RatingController::class, 'show'])->name('show');
        Route::get('{trackingCode}/edit', [RatingController::class, 'edit'])->name('edit');
        Route::put('{trackingCode}', [RatingController::class, 'update'])->name('update');
        Route::get('units/{unit}', [RatingController::class, 'unitRatings'])->name('unit');
        Route::get('{rating}/report', [ReportController::class, 'create'])->name('report.create');
        Route::get('{rating}/can-report', [ReportController::class, 'checkCanReport'])->name('report.check-can-report');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('history', [ReportController::class, 'history'])->name('history');
        Route::post('/', [ReportController::class, 'store'])->name('store');
        Route::get('{trackingCode}', [ReportController::class, 'show'])->name('show');
        Route::put('{trackingCode}', [ReportController::class, 'update'])->name('update');
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