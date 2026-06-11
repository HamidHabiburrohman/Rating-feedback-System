<?php

namespace App\Http\Controllers\Employee\Auth;

use App\Http\Controllers\Controller;
use App\Models\Authentication\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            abort(404);
        }

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetRecord ||
            !Hash::check($token, $resetRecord->token) ||
            Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            return redirect()->route('employee.password.request')
                ->withErrors(['email' => 'Link reset tidak valid atau sudah kadaluarsa.']);
        }

        return view('auth.employee.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord ||
            !Hash::check($request->token, $resetRecord->token) ||
            Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Link reset tidak valid atau sudah kadaluarsa.'
                ], 422);
            }

            return back()->withErrors(['email' => 'Link reset tidak valid atau sudah kadaluarsa.']);
        }

        /** @var Employee|null $employee */
        $employee = Employee::where('email', $request->email)->first();

        if ($employee) {
            try {
                $employee->password = Hash::make($request->password);
                $employee->save();

                DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->delete();

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Password berhasil direset.',
                        'redirect' => route('employee.login')
                    ]);
                }

                return redirect()->route('employee.login')
                    ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
            } catch (\Exception $e) {
                Log::error('Gagal reset password employee: ' . $e->getMessage());

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mereset password.'
                    ], 500);
                }

                return back()->withErrors(['email' => 'Gagal mereset password.']);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan.'
            ], 404);
        }

        return back()->withErrors(['email' => 'Gagal mereset password.']);
    }
}