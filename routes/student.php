<?php

use App\Http\Controllers\Student\Auth\LoginController as StudentLoginController;
use App\Http\Controllers\Student\Auth\RegisterController as StudentRegisterController;
use App\Http\Controllers\Student\Auth\ForgotPasswordController as StudentForgotPasswordController;
use App\Http\Controllers\Student\Auth\ResetPasswordController as StudentResetPasswordController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\UnitController as StudentUnitController;
use App\Http\Controllers\Student\RatingController as StudentRatingController;
use App\Http\Controllers\Student\ReportController as StudentReportController;
use App\Http\Controllers\Student\QrCodeController as StudentQrCodeController;
use App\Http\Controllers\Student\NotificationController as StudentNotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('student')->name('student.')->group(function () {
    Route::prefix('units')->name('units.')->group(function () {
        Route::get('/', [StudentUnitController::class, 'index'])->name('index');
        Route::get('/{unit:slug}', [StudentUnitController::class, 'show'])->name('show');
        Route::post('/{unitId}/favorite', [StudentUnitController::class, 'toggleFavorite'])->name('favorite');
    });

    Route::middleware('guest:student')->group(function () {
        Route::get('/login', [StudentLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [StudentLoginController::class, 'login'])->name('login.submit');
        Route::get('/register', [StudentRegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [StudentRegisterController::class, 'register'])->name('register.submit');
        Route::get('/forgot-password', [StudentForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/forgot-password', [StudentForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/reset-password', [StudentResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [StudentResetPasswordController::class, 'reset'])->name('password.update');
    });

    Route::middleware('auth:student')->group(function () {
        Route::post('/auth/logout', [StudentLoginController::class, 'logout'])->name('auth.logout');
        Route::get('/auth/check', [StudentLoginController::class, 'check'])->name('auth.check');

        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/', [StudentDashboardController::class, 'index'])->name('index');
        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [StudentProfileController::class, 'show'])->name('show');
            Route::get('/edit', [StudentProfileController::class, 'edit'])->name('edit');
            Route::put('/', [StudentProfileController::class, 'update'])->name('update');
            Route::put('/password', [StudentProfileController::class, 'updatePassword'])->name('password.update');
            Route::get('/sessions', [StudentProfileController::class, 'sessions'])->name('sessions');
            Route::delete('/sessions/{sessionId}', [StudentProfileController::class, 'terminateSession'])->name('terminate-session');
            Route::delete('/sessions/all/terminate', [StudentProfileController::class, 'terminateAllSessions'])->name('terminate-all');
            Route::post('/favorite-unit/{unitId}', [StudentProfileController::class, 'toggleFavorite'])->name('toggle-favorite');
        });

        Route::prefix('qr')->name('qr.')->group(function () {
            Route::get('/scan', [StudentQrCodeController::class, 'scanForm'])->name('scan');
            Route::get('/result', [StudentQrCodeController::class, 'scanResult'])->name('result');
            Route::post('/validate', [StudentQrCodeController::class, 'validateQr'])->name('validate');
            Route::get('/unit/{unitId}/status', [StudentQrCodeController::class, 'checkStatus'])->name('status');
        });

        Route::prefix('ratings')->name('ratings.')->group(function () {
            Route::get('/history', [StudentRatingController::class, 'history'])->name('history');
            Route::get('/categories', [StudentRatingController::class, 'categories'])->name('categories');
            Route::get('/create/{unit:slug}', [StudentRatingController::class, 'create'])->name('create');
            Route::post('/', [StudentRatingController::class, 'store'])->name('store');
            Route::get('/unit/{unit:slug}/ratings', [StudentRatingController::class, 'unitRatings'])->name('unit');
            Route::get('/{trackingCode}', [StudentRatingController::class, 'show'])->name('show');
            Route::get('/{trackingCode}/edit', [StudentRatingController::class, 'edit'])->name('edit');
            Route::put('/{trackingCode}', [StudentRatingController::class, 'update'])->name('update');
            Route::delete('/{trackingCode}', [StudentRatingController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/history', [StudentReportController::class, 'history'])->name('history');
            Route::get('/check/{rating}/can-report', [StudentReportController::class, 'checkCanReport'])->name('check-can-report');
            Route::get('/create/{rating}', [StudentReportController::class, 'create'])->name('create');
            Route::post('/', [StudentReportController::class, 'store'])->name('store');
            Route::get('/{trackingCode}', [StudentReportController::class, 'show'])->name('show');
            Route::get('/{trackingCode}/edit', [StudentReportController::class, 'edit'])->name('edit');
            Route::put('/{trackingCode}', [StudentReportController::class, 'update'])->name('update');
            Route::delete('/{trackingCode}', [StudentReportController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [StudentNotificationController::class, 'index'])->name('index');
            Route::put('/{id}/read', [StudentNotificationController::class, 'markAsRead'])->name('mark-read');
            Route::put('/mark-all-read', [StudentNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
            Route::delete('/{id}', [StudentNotificationController::class, 'destroy'])->name('destroy');
            Route::get('/unread-count', [StudentNotificationController::class, 'unreadCount'])->name('unread-count');
        });
    });
});