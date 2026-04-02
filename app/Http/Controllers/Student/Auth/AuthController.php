<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Auth\StudentLoginRequest;
use App\Services\Student\Auth\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function showLogin()
    {
        if (auth('student')->check()) {
            return redirect()->route('student.dashboard');
        }

        return view('auth.student.login');
    }

    public function login(StudentLoginRequest $request)
    {
        try {
            $student = $this->service->login(
                $request->student_identifier,
                $request->password,
                $request->ip(),
                $request->userAgent()
            );

            return redirect()->intended(route('student.units.index'))
                ->with('success', 'Selamat datang, ' . $student->name);
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $this->service->logout();

        return redirect()->route('student.login')
            ->with('success', 'Berhasil logout');
    }

    public function showRegistrationForm()
    {
        if (auth('student')->check()) {
            return redirect()->route('student.dashboard');
        }

        return view('auth.student.register');
    }

    public function register(Request $request)
    {
        // Implementasi register jika diperlukan
        return redirect()->route('student.login')
            ->with('info', 'Fitur registrasi akan segera hadir');
    }

    public function check(Request $request)
    {
        if (!auth('student')->check()) {
            return response()->json(['authenticated' => false, 'message' => 'Tidak ada sesi aktif']);
        }

        return response()->json([
            'authenticated' => true,
            'student' => [
                'id' => auth('student')->id(),
                'name' => auth('student')->user()->name,
                'identifier' => auth('student')->user()->student_identifier
            ]
        ]);
    }

    
}