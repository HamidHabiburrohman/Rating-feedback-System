<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('employee')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('employee.login');
        }

        $employee = Auth::guard('employee')->user();
        
        if (!$employee->is_active) {
            Auth::guard('employee')->logout();
            
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Account is disabled'], 403);
            }
            return redirect()->route('employee.login')->with('error', 'Akun Anda dinonaktifkan');
        }

        return $next($request);
    }
}