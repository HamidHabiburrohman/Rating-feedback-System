<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class GuestStudentMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.dashboard.index');
        }

        return $next($request);
    }
}