<?php

namespace App\Http\Controllers\Employee\Auth;

use App\Http\Controllers\Controller;
use App\Models\Authentication\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.employee.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        /** @var Employee|null $employee */
        $employee = Employee::where('email', $request->email)->first();

        if (!$employee) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan dalam sistem kami.',
            ])->withInput();
        }

        if (!$employee->is_active) {
            return back()->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.',
            ])->withInput();
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $employee->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        $resetUrl = route('employee.password.reset', [
            'token' => $token,
            'email' => $employee->email,
        ]);

        try {
            // Mail::to($employee->email)->send(new EmployeeResetPasswordMail($resetUrl, $employee->name));

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Link reset password telah dikirim ke email Anda.'
                ]);
            }

            return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            Log::error('Gagal kirim email reset password employee: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim email. Silakan coba lagi nanti.'
                ], 500);
            }

            return back()->with('error', 'Gagal mengirim email. Silakan coba lagi nanti.');
        }
    }
}