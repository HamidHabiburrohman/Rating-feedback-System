<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = null;

        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
        } elseif (Auth::guard('employee')->check()) {
            $user = Auth::guard('employee')->user();
        } elseif (Auth::guard('student')->check()) {
            $user = Auth::guard('student')->user();
        } elseif (Auth::check()) {
            $user = Auth::user();
        }

        if (!$user) {
            if ($request->is('admin/*') || $request->is('admin')) {
                return redirect()->route('admin.login');
            }
            if ($request->is('employee/*') || $request->is('employee')) {
                return redirect()->route('employee.login');
            }
            if ($request->is('student/*') || $request->is('student')) {
                return redirect()->route('student.login');
            }
            return redirect()->route('landing.index');
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}