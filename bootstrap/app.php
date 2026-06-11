<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
            
            Route::middleware(['web', 'employee'])
                ->prefix('employee')
                ->name('employee.')
                ->group(base_path('routes/employee.php'));
            
            Route::middleware(['web', 'student'])
                ->prefix('student')
                ->name('student.')
                ->group(base_path('routes/student.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'employee' => \App\Http\Middleware\EmployeeMiddleware::class,
            'student' => \App\Http\Middleware\StudentMiddleware::class,
            'guest' => \App\Http\Middleware\GuestMiddleware::class,
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
        
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();