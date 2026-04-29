<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\GuestStudentMiddleware;
use App\Http\Middleware\StudentAuthMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // Admin Middleware
            'admin' => AdminMiddleware::class,
            'role' => CheckRole::class,
            
            // Student Middleware
            'student.auth' => StudentAuthMiddleware::class,
            'guest.student' => GuestStudentMiddleware::class,
        ]);

        $middleware->web(append: []);

        $middleware->api(append: []);

        $middleware->group('admin.api', [
            'auth:sanctum',
            'admin',
            'throttle:60,1',
        ]);

        $middleware->group('visitor.api', [
            'throttle:100,1',
        ]);

        $middleware->group('rating.submission', [
            'throttle:60,1',
        ]);

        $middleware->priority([
            \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
            \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
            \Illuminate\Auth\Middleware\Authorize::class,
            StudentAuthMiddleware::class,
            GuestStudentMiddleware::class,
            AdminMiddleware::class,
            CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();