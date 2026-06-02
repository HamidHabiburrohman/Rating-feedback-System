<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
            $student = Auth::guard('student')->user();

            Student::where('id', $student->id)->update(['last_login_at' => Carbon::now()]);

            $request->session()->regenerate();

            return redirect()->intended(route('student.units.index'));
        }

        return back()->withErrors([
            'student_identifier' => 'NIM atau password salah.',
        ])->onlyInput('student_identifier');
    }

    public function showRegistrationForm()
    {
        return view('auth.student.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_identifier' => 'required|string|unique:students,student_identifier',
            'password' => 'required|string|min:6|confirmed',
            'terms' => 'required|accepted',
            [
                'terms.required' => 'Anda harus menyetujui Syarat & Ketentuan.',
                'terms.accepted' => 'Anda harus menyetujui Syarat & Ketentuan.',
            ]
        ]);

        $student = Student::create([
            'name' => $validated['name'],
            'student_identifier' => $validated['student_identifier'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::guard('student')->login($student);

        return redirect()->route('student.units.index')->with('success', 'Registration successful! Welcome to Itenas Portal.');
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
