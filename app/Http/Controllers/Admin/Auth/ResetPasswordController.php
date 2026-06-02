<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            return back()->withErrors(['email' => 'Link reset tidak valid atau sudah kadaluarsa.']);
        }

        $admin = Admin::where('email', $request->email)->first();
        if ($admin) {
            $admin->password = Hash::make($request->password);
            $admin->save();

            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return redirect()->route('admin.login')
                ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
        }

        return back()->withErrors(['email' => 'Gagal mereset password.']);
    }
}