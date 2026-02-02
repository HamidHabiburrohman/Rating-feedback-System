<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->onlyInput('email');
        }

        if (!in_array($user->role, ['admin', 'super_admin'])) {
            return back()->withErrors([
                'email' => 'Access denied. Admin only.',
            ]);
        }

        // **SANCTUM LOGIN: Buat token dan login**
        $token = $user->createToken('admin-web-session', ['admin:access'])->plainTextToken;
        
        // **PENTING: Simpan token di session dan cookie**
        $request->session()->put('sanctum_token', $token);
        
        // Login user dengan sanctum (bukan Auth::login)
        Auth::guard('web')->login($user, $request->has('remember'));
        
        // **Set Sanctum token di cookie untuk middleware auth:sanctum**
        $cookie = cookie('sanctum_token', $token, 60 * 24 * 30); // 30 days
        
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'))
            ->with('success', 'Welcome back, ' . $user->nama . '!')
            ->withCookie($cookie); // Attach cookie to response
    }
}