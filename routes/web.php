<?php

use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::prefix('legal')->name('legal.')->group(function () {
    Route::view('/privacy-policy', 'legal.privacy')->name('privacy');
    Route::view('/terms-of-service', 'legal.terms')->name('terms');
    Route::view('/cookie-policy', 'legal.cookies')->name('cookies');
});

Route::prefix('docs')->name('docs.')->group(function () {
    Route::view('/', 'docs.index')->name('index');
    Route::view('/api', 'docs.api')->name('api');
    Route::view('/user-guide', 'docs.user-guide')->name('user-guide');
    Route::view('/faq', 'docs.faq')->name('faq');
});

Route::prefix('contact')->name('contact.')->group(function () {
    Route::view('/', 'contact.index')->name('index');
    Route::view('/support', 'contact.support')->name('support');
});

Route::prefix('errors')->name('errors.')->group(function () {
    Route::view('/403', 'errors.403')->name('403');
    Route::view('/404', 'errors.404')->name('404');
    Route::view('/419', 'errors.419')->name('419');
    Route::view('/500', 'errors.500')->name('500');
});

Route::get('/login', function () {
    if (Auth::guard('admin')->check()) return redirect()->route('admin.dashboard.index');
    if (Auth::guard('employee')->check()) return redirect()->route('employee.dashboard.index');
    if (Auth::guard('student')->check()) return redirect()->route('student.units.index');
    return redirect()->route('admin.login');
})->name('login');

Route::post('/logout', function () {
    if (Auth::guard('admin')->check()) return redirect()->route('admin.logout');
    if (Auth::guard('employee')->check()) return redirect()->route('employee.logout');
    if (Auth::guard('student')->check()) return redirect()->route('student.auth.logout');
    return redirect()->route('home');
})->name('logout');

Route::fallback(fn() => view('errors.404'));