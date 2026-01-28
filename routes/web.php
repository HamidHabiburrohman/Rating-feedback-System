<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UnitTypeController;
use App\Http\Controllers\Auth\WebLoginController;
use App\Http\Controllers\Auth\LogoutController;

/*
|--------------------------------------------------------------------------
| Public Routes (Tanpa Autentikasi)
|--------------------------------------------------------------------------
*/

// Halaman utama redirect ke login
Route::get('/', function () {
    return view('auth.login');
});

// Routes untuk Visitor (Public)
Route::prefix('visitor')->name('visitor.')->group(function () {
    Route::get('/rate/{unit}', function ($unitCode) {
        return view('visitor.rate', ['unitCode' => $unitCode]);
    })->name('rate');
    
    Route::get('/thank-you', function () {
        return view('visitor.thank-you');
    })->name('thank-you');
    
    Route::get('/browse', function () {
        return view('visitor.browse');
    })->name('browse');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Login/Register/Logout)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::post('/login', [WebLoginController::class, 'login']);
    
    // Register (jika diperlukan)
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    
    // Forgot Password
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
    
    Route::get('/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');
});

// Logout (bisa diakses oleh user yang login)
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
Route::post('/logout-all', [LogoutController::class, 'logoutAll'])->name('logout.all');

/*
|--------------------------------------------------------------------------
| Admin Routes (Dengan Autentikasi)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->prefix('admin')->name('admin.')->group(function () {
    
    /*
    |--------------------------------------------------------------------------
    | Unit Type Management Routes
    |--------------------------------------------------------------------------
    | Routes untuk mengelola tipe unit (master data)
    */
    Route::prefix('unit-types')->name('unit-types.')->group(function () {
        // CRUD Operations
        Route::get('/', [UnitTypeController::class, 'index'])->name('index');
        Route::get('/create', [UnitTypeController::class, 'create'])->name('create');
        Route::post('/', [UnitTypeController::class, 'store'])->name('store');
        Route::get('/{unit_type}', [UnitTypeController::class, 'show'])->name('show');
        Route::get('/{unit_type}/edit', [UnitTypeController::class, 'edit'])->name('edit');
        Route::put('/{unit_type}', [UnitTypeController::class, 'update'])->name('update');
        Route::delete('/{unit_type}', [UnitTypeController::class, 'destroy'])->name('destroy');
        
        // Additional Unit Type Actions
        Route::post('/{unit_type}/toggle-status', [UnitTypeController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/reorder', [UnitTypeController::class, 'reorder'])->name('reorder');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Unit Management Routes
    |--------------------------------------------------------------------------
    | Routes untuk mengelola unit operasional
    */
    Route::prefix('units')->name('units.')->group(function () {
        // CRUD Operations
        Route::get('/', [UnitController::class, 'index'])->name('index');
        Route::get('/create', [UnitController::class, 'create'])->name('create');
        Route::post('/', [UnitController::class, 'store'])->name('store');
        Route::get('/{unit}', [UnitController::class, 'show'])->name('show');
        Route::get('/{unit}/edit', [UnitController::class, 'edit'])->name('edit');
        Route::put('/{unit}', [UnitController::class, 'update'])->name('update');
        Route::delete('/{unit}', [UnitController::class, 'destroy'])->name('destroy');
        
        // Additional Unit Actions
        Route::post('/{unit}/toggle-status', [UnitController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/{unit}/categories', [UnitController::class, 'categories'])->name('categories');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Other Admin Module Routes
    |--------------------------------------------------------------------------
    */
    
    // Employees Management
    Route::get('/employees', function () {
        return view('admin.employees.index');
    })->name('employees.index');
    
    // Messages/Inbox
    Route::get('/messages', function () {
        return view('admin.messages.index');
    })->name('messages.index');
    
    // Ratings/Reviews
    Route::prefix('ratings')->name('ratings.')->group(function () {
        Route::get('/', function () {
            return view('admin.ratings.index');
        })->name('index');
        
        Route::get('/{rating}', function ($rating) {
            return view('admin.ratings.show', ['ratingId' => $rating]);
        })->name('show');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', function () {
            return view('admin.reports.index');
        })->name('index');
        
        Route::get('/{report}', function ($report) {
            return view('admin.reports.show', ['reportId' => $report]);
        })->name('show');
    });
    
    // Analytics Dashboard
    Route::get('/analytics', function () {
        return view('admin.analytics.index');
    })->name('analytics.index');
    
    // Data Export
    Route::get('/export', function () {
        return view('admin.export.index');
    })->name('export.index');
    
    // User Management
    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('users.index');
    
    // System Settings
    Route::get('/settings', function () {
        return view('admin.settings.index');
    })->name('settings.index');
    
    // Audit Logs
    Route::get('/audit-logs', function () {
        return view('admin.audit-logs.index');
    })->name('audit-logs.index');
    
    /*
    |--------------------------------------------------------------------------
    | Admin SPA Fallback Route
    |--------------------------------------------------------------------------
    | Menangani semua route admin lainnya untuk SPA (Single Page Application)
    */
    Route::get('/{any?}', function () {
        return view('admin.dashboard');
    })->where('any', '.*')->name('spa');
});

/*
|--------------------------------------------------------------------------
| Documentation & Help Routes
|--------------------------------------------------------------------------
*/
Route::prefix('docs')->name('docs.')->group(function () {
    Route::get('/', function () {
        return view('docs.index');
    })->name('index');
    
    Route::get('/api', function () {
        return view('docs.api');
    })->name('api');
});

Route::prefix('help')->name('help.')->group(function () {
    Route::get('/', function () {
        return view('help.index');
    })->name('index');
    
    Route::get('/faq', function () {
        return view('help.faq');
    })->name('faq');
});

/*
|--------------------------------------------------------------------------
| Contact & Support Routes
|--------------------------------------------------------------------------
*/
Route::prefix('contact')->name('contact.')->group(function () {
    Route::get('/', function () {
        return view('contact.index');
    })->name('index');
    
    Route::get('/support', function () {
        return view('contact.support');
    })->name('support');
});

/*
|--------------------------------------------------------------------------
| Legal Pages
|--------------------------------------------------------------------------
*/
Route::get('/privacy-policy', function () {
    return view('legal.privacy');
})->name('privacy');

Route::get('/terms-of-service', function () {
    return view('legal.terms');
})->name('terms');

Route::get('/cookie-policy', function () {
    return view('legal.cookies');
})->name('cookies');

/*
|--------------------------------------------------------------------------
| Error Pages
|--------------------------------------------------------------------------
*/
Route::get('/404', function () {
    return view('errors.404');
})->name('404');

Route::get('/500', function () {
    return view('errors.500');
})->name('500');

Route::get('/403', function () {
    return view('errors.403');
})->name('403');

Route::get('/419', function () {
    return view('errors.419');
})->name('419');

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return view('errors.404');
});