<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Employee\Auth\LoginController;
use App\Http\Controllers\Employee\Auth\ForgotPasswordController;
use App\Http\Controllers\Employee\Auth\ResetPasswordController;
use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\Employee\ProfileController;
use App\Http\Controllers\Employee\UnitController;
use App\Http\Controllers\Employee\RatingController;
use App\Http\Controllers\Employee\ReportController;

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

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
Route::get('dashboard/recent-ratings', [DashboardController::class, 'recentRatings'])->name('dashboard.recent-ratings');
Route::get('dashboard/recent-reports', [DashboardController::class, 'recentReports'])->name('dashboard.recent-reports');

Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::put('profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
Route::post('profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
Route::delete('profile/remove-photo', [ProfileController::class, 'removePhoto'])->name('profile.remove-photo');
Route::put('profile/update-preferences', [ProfileController::class, 'updatePreferences'])->name('profile.update-preferences');

Route::get('units', [UnitController::class, 'index'])->name('units.index');
Route::get('units/{id}', [UnitController::class, 'show'])->name('units.show');
Route::get('units/{id}/edit', [UnitController::class, 'edit'])->name('units.edit');
Route::put('units/{id}', [UnitController::class, 'update'])->name('units.update');
Route::get('units/{unitId}/rating-categories', [UnitController::class, 'ratingCategories'])->name('units.rating-categories');

Route::get('ratings', [RatingController::class, 'index'])->name('ratings.index');
Route::get('ratings/{id}', [RatingController::class, 'show'])->name('ratings.show');
Route::post('ratings/{ratingId}/reply', [RatingController::class, 'storeReply'])->name('ratings.reply.store');
Route::put('ratings/reply/{replyId}', [RatingController::class, 'updateReply'])->name('ratings.reply.update');
Route::delete('ratings/reply/{replyId}', [RatingController::class, 'destroyReply'])->name('ratings.reply.destroy');
Route::get('ratings/{ratingId}/replies', [RatingController::class, 'getReplies'])->name('ratings.replies');

Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('reports/{id}', [ReportController::class, 'show'])->name('reports.show');
Route::post('reports/{id}/update-status', [ReportController::class, 'updateStatus'])->name('reports.update-status');
Route::post('reports/{id}/resolve', [ReportController::class, 'resolve'])->name('reports.resolve');
Route::post('reports/{id}/reopen', [ReportController::class, 'reopen'])->name('reports.reopen');
Route::post('reports/{reportId}/reply', [ReportController::class, 'storeReply'])->name('reports.reply.store');
Route::put('reports/reply/{replyId}', [ReportController::class, 'updateReply'])->name('reports.reply.update');
Route::delete('reports/reply/{replyId}', [ReportController::class, 'destroyReply'])->name('reports.reply.destroy');
Route::get('reports/{id}/history', [ReportController::class, 'history'])->name('reports.history');