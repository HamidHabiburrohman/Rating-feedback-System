<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('admin.login');
        }

        $admin = Auth::guard('admin')->user();
        
        if (!$admin->is_active) {
            Auth::guard('admin')->logout();
            
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Account is disabled'], 403);
            }
            return redirect()->route('admin.login')->with('error', 'Akun Anda dinonaktifkan');
        }

        return $next($request);
    }
}