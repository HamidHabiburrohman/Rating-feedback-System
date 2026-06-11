<?php

namespace App\Http\Controllers\Employee\Auth;

use App\Http\Controllers\Controller;
use App\Models\Authentication\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('employee')->check()) {
            return redirect()->route('employee.dashboard.index');
        }
        
        return view('auth.employee.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        /** @var Employee|null $employee */
        $employee = Employee::where('email', $request->email)->first();

        if ($employee && !$employee->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Akun Anda telah dinonaktifkan. Silakan hubungi administrator.'],
            ]);
        }

        if (Auth::guard('employee')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            /** @var Employee $authenticatedEmployee */
            $authenticatedEmployee = Auth::guard('employee')->user();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'redirect' => route('employee.dashboard.index')
                ]);
            }

            return redirect()->intended(route('employee.dashboard.index'))
                ->with('success', 'Selamat datang kembali, ' . ($authenticatedEmployee->name ?? 'Employee') . '!');
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
        Auth::guard('employee')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Anda telah berhasil logout'
            ]);
        }

        return redirect()->route('employee.login')
            ->with('success', 'Anda telah berhasil logout.');
    }

    public function check()
    {
        if (Auth::guard('employee')->check()) {
            /** @var Employee $employee */
            $employee = Auth::guard('employee')->user();
            
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'photo' => $employee->photo,
                ]
            ]);
        }

        return response()->json([
            'authenticated' => false, 
            'user' => null
        ]);
    }
}