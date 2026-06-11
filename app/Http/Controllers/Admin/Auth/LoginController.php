<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Authentication\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
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

        /** @var \App\Models\Authentication\Admin|null $admin */
        $admin = Admin::where('email', $request->email)->first();

        if ($admin && !$admin->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Akun Anda telah dinonaktifkan. Silakan hubungi administrator.'],
            ]);
        }

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            /** @var \App\Models\Authentication\Admin $authenticatedAdmin */
            $authenticatedAdmin = Auth::guard('admin')->user();
            
            if (method_exists($authenticatedAdmin, 'updateLastLogin')) {
                $authenticatedAdmin->updateLastLogin($request->ip());
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'redirect' => route('admin.dashboard')
                ]);
            }

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang kembali, ' . ($authenticatedAdmin->nama ?? 'Admin') . '!');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => trans('auth.failed')
            ], 422);
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

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Anda telah berhasil logout'
            ]);
        }

        return redirect()->route('admin.login')
            ->with('success', 'Anda telah berhasil logout.');
    }

    public function check()
    {
        if (Auth::guard('admin')->check()) {
            /** @var \App\Models\Authentication\Admin $admin */
            $admin = Auth::guard('admin')->user();
            
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'id' => $admin->id,
                    'name' => $admin->nama,
                    'email' => $admin->email,
                    'role' => $admin->role,
                    'photo' => $admin->photo,
                ]
            ]);
        }

        return response()->json([
            'authenticated' => false, 
            'user' => null
        ]);
    }
}