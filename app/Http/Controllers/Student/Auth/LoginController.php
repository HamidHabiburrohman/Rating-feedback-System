<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use Illuminate\Support\Carbon;

class LoginController extends Controller
{
    public function showLoginForm()
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

    public function logout(Request $request)
    {
        // Implement logout logic
    }
}
