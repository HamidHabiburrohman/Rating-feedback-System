<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\RatingController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UnitTypeController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Tanpa Autentikasi)
|--------------------------------------------------------------------------
| Routes yang bisa diakses semua orang termasuk visitor
*/

// Halaman utama - Redirect ke halaman visitor
Route::get('/', function () {
    return view('admin.auth.login');
});

/*
|--------------------------------------------------------------------------
| VISITOR ROUTES
|--------------------------------------------------------------------------
| Routes khusus untuk visitor tanpa perlu login
*/
Route::prefix('visitor')->name('visitor.')->group(function () {
    // Submit rating untuk unit tertentu
    Route::get('/rate/{unit}', function ($unitCode) {
        return view('visitor.rate', ['unitCode' => $unitCode]);
    })->name('rate');

    // Halaman thank you setelah submit rating
    Route::get('/thank-you', function () {
        return view('visitor.thank-you');
    })->name('thank-you');

    // Browse units available
    Route::get('/browse', function () {
        return view('visitor.browse');
    })->name('browse');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES (Login/Logout)
|--------------------------------------------------------------------------
| Routes untuk autentikasi - HANYA ADMIN
*/

// Login routes - hanya untuk admin
Route::middleware('guest')->group(function () {
    // Halaman login admin
    Route::get('/admin/login', function () {
        return view('admin.auth.login');
    })->name('admin.login');

    // Proses login admin
    Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.submit');
});

// Logout - hanya untuk yang sudah login
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Dengan Autentikasi)
|--------------------------------------------------------------------------
| Routes yang hanya bisa diakses oleh admin yang sudah login
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');


    Route::prefix('dashboard')->group(function () {
        Route::get('/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
        Route::get('/charts', [DashboardController::class, 'charts'])->name('dashboard.charts');
        Route::get('/overview', [DashboardController::class, 'overview'])->name('dashboard.overview');
    });


    Route::get('reports/export', [ExportController::class, 'exportReports'])->name('reports.export');
    Route::get('ratings/export', [ExportController::class, 'exportRatings'])->name('ratings.export');
    Route::get('units/export', [ExportController::class, 'exportUnits'])->name('units.export');
    Route::get('unit-types/export', [ExportController::class, 'exportUnitTypes'])->name('unit-types.export');
    /*
    |--------------------------------------------------------------------------
    | UNIT TYPE MANAGEMENT ROUTES
    |--------------------------------------------------------------------------
    | CRUD untuk tipe unit (kategori unit)
    */
    Route::prefix('unit-types')->name('unit-types.')->group(function () {
        Route::get('/', [UnitTypeController::class, 'index'])->name('index');
        Route::get('/create', [UnitTypeController::class, 'create'])->name('create');
        Route::post('/', [UnitTypeController::class, 'store'])->name('store');
        Route::get('/{unit_type}', [UnitTypeController::class, 'show'])->name('show');
        Route::get('/{unit_type}/edit', [UnitTypeController::class, 'edit'])->name('edit');
        Route::put('/{unit_type}', [UnitTypeController::class, 'update'])->name('update');
        Route::delete('/{unit_type}', [UnitTypeController::class, 'destroy'])->name('destroy');
        Route::post('/{unit_type}/toggle-status', [UnitTypeController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/reorder', [UnitTypeController::class, 'reorder'])->name('reorder');
        Route::post('unit-types/{id}/toggle-status', [UnitTypeController::class, 'toggleStatus'])
            ->name('unit-types.toggle-status');
    });

    /*
    |--------------------------------------------------------------------------
    | UNIT MANAGEMENT ROUTES
    |--------------------------------------------------------------------------
    | CRUD untuk unit (department/faculty)
    */
    Route::prefix('units')->name('units.')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->name('index');
        Route::get('/create', [UnitController::class, 'create'])->name('create');
        Route::post('/', [UnitController::class, 'store'])->name('store');
        Route::get('/{unit}', [UnitController::class, 'show'])->name('show');
        Route::get('/{unit}/edit', [UnitController::class, 'edit'])->name('edit');
        Route::put('/{unit}', [UnitController::class, 'update'])->name('update');
        Route::delete('/{unit}', [UnitController::class, 'destroy'])->name('destroy');
        Route::post('/{unit}/toggle-status', [UnitController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/{unit}/categories', [UnitController::class, 'categories'])->name('categories');

    });

    /*
    |--------------------------------------------------------------------------
    | RATING MANAGEMENT ROUTES
    |--------------------------------------------------------------------------
    | CRUD untuk rating & feedback dari visitor
    */
    Route::prefix('ratings')->name('ratings.')->group(function () {
        Route::get('/', [RatingController::class, 'index'])->name('index');
        Route::get('/{id}', [RatingController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [RatingController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RatingController::class, 'update'])->name('update');
        Route::delete('/{id}', [RatingController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/respond', [RatingController::class, 'respond'])->name('respond');
        Route::post('/{id}/complete', [RatingController::class, 'markAsCompleted'])->name('complete');
        Route::get('/analytics', [RatingController::class, 'analytics'])->name('analytics');
        Route::get('/export', [RatingController::class, 'export'])->name('export');
        Route::get('/stats', [RatingController::class, 'getStats'])->name('stats');
        Route::put('/admin/ratings/{id}/status', [RatingController::class, 'updateStatus'])->name('update-status');
        Route::post('/admin/ratings/{id}/reply', [RatingController::class, 'reply'])->name('reply');
        Route::get('ratings/{id}/reply', [RatingController::class, 'replyPage'])->name('reply-page');
    });

    /*
    |--------------------------------------------------------------------------
    | REPORT MANAGEMENT ROUTES
    |--------------------------------------------------------------------------
    | CRUD untuk laporan dan reporting system
    */
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/create', [ReportController::class, 'create'])->name('create');
        Route::get('/{id}', [ReportController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ReportController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ReportController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReportController::class, 'destroy'])->name('destroy');
        Route::post('/{report}/update-status', [ReportController::class, 'updateStatus'])->name('update-status');
        Route::get('/export', [ReportController::class, 'export'])->name('export');
    });

    /*
    |--------------------------------------------------------------------------
    | OTHER ADMIN MODULES
    |--------------------------------------------------------------------------
    | Modul tambahan untuk admin
    */

    // Analytics dashboard
    Route::get('/analytics', function () {
        return view('admin.analytics.index');
    })->name('analytics.index');

    // Export data
    Route::get('/export', function () {
        return view('admin.export.index');
    })->name('export.index');

    // User management
    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('users.index');

    // Settings
    Route::get('/settings', function () {
        return view('admin.settings.index');
    })->name('settings.index');

    // Audit logs
    Route::get('/audit-logs', function () {
        return view('admin.audit-logs.index');
    })->name('audit-logs.index');
});

/*
|--------------------------------------------------------------------------
| DOCUMENTATION & HELP ROUTES
|--------------------------------------------------------------------------
| Routes untuk dokumentasi dan bantuan
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
| CONTACT & SUPPORT ROUTES
|--------------------------------------------------------------------------
| Routes untuk kontak dan dukungan
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
| LEGAL PAGES
|--------------------------------------------------------------------------
| Halaman legal (privacy policy, terms, etc)
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
| ERROR PAGES
|--------------------------------------------------------------------------
| Custom error pages
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
| FALLBACK ROUTE
|--------------------------------------------------------------------------
| Route fallback untuk handle 404
*/
Route::fallback(function () {
    return view('errors.404');
});