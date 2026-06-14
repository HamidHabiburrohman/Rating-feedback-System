<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if (auth('employee')->check()) {
            return redirect()->route('employee.dashboard');
        }

        if (auth('student')->check()) {
            return redirect()->route('student.dashboard');
        }

        return $next($request);
    }
}