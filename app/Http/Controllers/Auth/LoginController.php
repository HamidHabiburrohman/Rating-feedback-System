<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
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

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            if (!in_array($user->role, ['admin', 'super_admin', 'unit'])) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => ['You do not have permission to access admin area.'],
                ]);
            }

            return redirect()->intended(route('admin.dashboard.index'))
                ->with('success', 'Welcome back, ' . $user->nama . '!');
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    // public function logout(Request $request)
    // {
    //     Auth::logout();

    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();

    //     return redirect()->route('admin.login')
    //         ->with('success', 'You have been logged out successfully.');
    // }
}