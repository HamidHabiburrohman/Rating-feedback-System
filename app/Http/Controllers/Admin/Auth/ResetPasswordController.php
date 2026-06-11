<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Authentication\Admin;
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
            return redirect()->route('admin.password.request')
                ->withErrors(['email' => 'Link reset tidak valid atau sudah kadaluarsa.']);
        }

        return view('auth.admin.reset-password', [
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

        /** @var \App\Models\Authentication\Admin|null $admin */
        $admin = Admin::where('email', $request->email)->first();

        if ($admin) {
            try {
                $admin->password = Hash::make($request->password);
                $admin->save();

                DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->delete();

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Password berhasil direset.',
                        'redirect' => route('admin.login')
                    ]);
                }

                return redirect()->route('admin.login')
                    ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
            } catch (\Exception $e) {
                Log::error('Gagal reset password admin: ' . $e->getMessage());

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