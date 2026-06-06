<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('student')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('student.login');
        }

        $student = Auth::guard('student')->user();
        
        if (!$student->is_active) {
            Auth::guard('student')->logout();
            
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Account is disabled'], 403);
            }
            return redirect()->route('student.login')->with('error', 'Akun Anda dinonaktifkan');
        }

        return $next($request);
    }
}