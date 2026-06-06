<?php

use App\Http\Controllers\Employee\Auth\LoginController as EmployeeLoginController;
use App\Http\Controllers\Employee\Auth\ForgotPasswordController as EmployeeForgotPasswordController;
use App\Http\Controllers\Employee\Auth\ResetPasswordController as EmployeeResetPasswordController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\ProfileController as EmployeeProfileController;
use App\Http\Controllers\Employee\UnitController as EmployeeUnitController;
use App\Http\Controllers\Employee\RatingController as EmployeeRatingController;
use App\Http\Controllers\Employee\ReportController as EmployeeReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('employee')->name('employee.')->group(function () {
    Route::middleware('guest:employee')->group(function () {
        Route::get('/login', [EmployeeLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [EmployeeLoginController::class, 'login'])->name('login.submit');
        Route::get('/forgot-password', [EmployeeForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('/forgot-password', [EmployeeForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('/reset-password', [EmployeeResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('/reset-password', [EmployeeResetPasswordController::class, 'reset'])->name('password.update');
    });

    Route::post('/logout', [EmployeeLoginController::class, 'logout'])->middleware('auth:employee')->name('logout');
    Route::get('/auth/check', [EmployeeLoginController::class, 'check'])->middleware('auth:employee')->name('auth.check');

    Route::middleware(['auth:employee'])->group(function () {
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/', [EmployeeDashboardController::class, 'index'])->name('index');
        });

        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [EmployeeProfileController::class, 'show'])->name('show');
            Route::get('/edit', [EmployeeProfileController::class, 'edit'])->name('edit');
            Route::put('/', [EmployeeProfileController::class, 'update'])->name('update');
            Route::put('/password', [EmployeeProfileController::class, 'updatePassword'])->name('password.update');
        });

        Route::prefix('units')->name('units.')->group(function () {
            Route::get('/', [EmployeeUnitController::class, 'index'])->name('index');
            Route::get('/{unit}', [EmployeeUnitController::class, 'show'])->name('show');
            Route::get('/{unit}/edit', [EmployeeUnitController::class, 'edit'])->name('edit');
            Route::put('/{unit}', [EmployeeUnitController::class, 'update'])->name('update');
        });

        Route::prefix('ratings')->name('ratings.')->group(function () {
            Route::get('/', [EmployeeRatingController::class, 'index'])->name('index');
            Route::get('/{rating}', [EmployeeRatingController::class, 'show'])->name('show');
            Route::post('/{rating}/reply', [EmployeeRatingController::class, 'reply'])->name('reply');
            Route::put('/replies/{reply}', [EmployeeRatingController::class, 'updateReply'])->name('update-reply');
            Route::delete('/replies/{reply}', [EmployeeRatingController::class, 'deleteReply'])->name('delete-reply');
        });

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [EmployeeReportController::class, 'index'])->name('index');
            Route::get('/{report}', [EmployeeReportController::class, 'show'])->name('show');
            Route::post('/{report}/reply', [EmployeeReportController::class, 'reply'])->name('reply');
            Route::put('/replies/{reply}', [EmployeeReportController::class, 'updateReply'])->name('update-reply');
            Route::delete('/replies/{reply}', [EmployeeReportController::class, 'deleteReply'])->name('delete-reply');
            Route::put('/{report}/status', [EmployeeReportController::class, 'updateStatus'])->name('update-status');
        });
    });
});