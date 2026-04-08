<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.student.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'student_identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::guard('student')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('student.dashboard.index'));
        }

        return back()->withErrors([
            'student_identifier' => 'The provided credentials do not match our records.',
        ])->onlyInput('student_identifier');
    }

    public function showRegistrationForm()
    {
        return view('student.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'student_identifier' => 'required|string|unique:students',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $student = Student::create($validated);

        Auth::guard('student')->login($student);

        return redirect()->route('student.dashboard.index');
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login');
    }

    public function check()
    {
        return response()->json([
            'authenticated' => Auth::guard('student')->check(),
            'user' => Auth::guard('student')->user()
        ]);
    }
}