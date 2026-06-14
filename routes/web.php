<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;

Route::get('/', [LandingPageController::class, 'index'])->name('landing.index');

Route::get('/home', function () {
    if (auth('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }
    
    if (auth('employee')->check()) {
        return redirect()->route('employee.dashboard');
    }
    
    if (auth('student')->check()) {
        return redirect()->route('student.dashboard');
    }
    
    return redirect()->route('landing.index');
})->name('home');