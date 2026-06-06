<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard.index');
        }
        
        return view('auth.admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $admin = Auth::guard('admin')->user();
            
            if ($admin && method_exists($admin, 'updateLastLogin')) {
                $admin->updateLastLogin($request->ip());
            }

            return redirect()->intended(route('admin.dashboard.index'))
                ->with('success', 'Welcome back, ' . ($admin->nama ?? 'Admin') . '!');
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }

    public function check()
    {
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'id' => $admin->id,
                    'name' => $admin->nama,
                    'email' => $admin->email,
                    'role' => $admin->role,
                ]
            ]);
        }

        return response()->json(['authenticated' => false, 'user' => null]);
    }
}