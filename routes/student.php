<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\Auth\LoginController;
use App\Http\Controllers\Student\Auth\RegisterController;
use App\Http\Controllers\Student\Auth\ForgotPasswordController;
use App\Http\Controllers\Student\Auth\ResetPasswordController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\NotificationController;
use App\Http\Controllers\Student\UnitController;
use App\Http\Controllers\Student\QrCodeController;
use App\Http\Controllers\Student\RatingController;
use App\Http\Controllers\Student\ReportController;

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

        return redirect()->route('student.login')
            ->with('success', 'Email berhasil diverifikasi! Silakan login.');
    })->name('verify.email');
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('check-auth', [LoginController::class, 'check'])->name('auth.check');

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
Route::get('dashboard/activity-chart', [DashboardController::class, 'activityChart'])->name('dashboard.activity-chart');
Route::get('dashboard/recommended-units', [DashboardController::class, 'recommendedUnits'])->name('dashboard.recommended-units');

Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::put('profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
Route::get('profile/sessions', [ProfileController::class, 'sessions'])->name('profile.sessions');
Route::post('profile/sessions/{sessionId}/terminate', [ProfileController::class, 'terminateSession'])->name('profile.sessions.terminate');
Route::post('profile/sessions/terminate-all', [ProfileController::class, 'terminateAllSessions'])->name('profile.sessions.terminate-all');

Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
Route::post('notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');

Route::get('units', [UnitController::class, 'index'])->name('units.index');
Route::get('units/{slug}', [UnitController::class, 'show'])->name('units.show');

Route::get('qr/scan', [QrCodeController::class, 'scanForm'])->name('qr.scan');
Route::get('qr/result', [QrCodeController::class, 'scanResult'])->name('qr.result');
Route::post('qr/validate', [QrCodeController::class, 'validateQr'])->name('qr.validate');
Route::get('qr/check-status/{unitId}', [QrCodeController::class, 'checkStatus'])->name('qr.check-status');

Route::get('units/{unit}/rate', [RatingController::class, 'create'])->name('ratings.create');
Route::post('ratings', [RatingController::class, 'store'])->name('ratings.store');
Route::get('ratings/{trackingCode}', [RatingController::class, 'show'])->name('ratings.show');
Route::get('ratings/{trackingCode}/edit', [RatingController::class, 'edit'])->name('ratings.edit');
Route::put('ratings/{trackingCode}', [RatingController::class, 'update'])->name('ratings.update');
Route::get('ratings/history', [RatingController::class, 'history'])->name('ratings.history');
Route::get('units/{unit}/ratings', [RatingController::class, 'unitRatings'])->name('ratings.unit');

Route::get('ratings/{rating}/report', [ReportController::class, 'create'])->name('reports.create');
Route::post('reports', [ReportController::class, 'store'])->name('reports.store');
Route::get('reports/{trackingCode}', [ReportController::class, 'show'])->name('reports.show');
Route::put('reports/{trackingCode}', [ReportController::class, 'update'])->name('reports.update');
Route::get('reports/history', [ReportController::class, 'history'])->name('reports.history');
Route::get('ratings/{rating}/can-report', [ReportController::class, 'checkCanReport'])->name('reports.check-can-report');
