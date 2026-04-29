<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        $identifier = $request->query('identifier');

        if (!$token || !$identifier) {
            abort(404);
        }

        // Cek apakah token valid
        $resetRecord = DB::table('student_password_resets')
            ->where('student_identifier', $identifier)
            ->first();

        if (!$resetRecord ||
            !Hash::check($token, $resetRecord->token) ||
            Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            return redirect()->route('student.password.request')
                ->withErrors(['email' => 'Link reset tidak valid atau sudah kadaluarsa.']);
        }

        return view('auth.student.reset-password', [
            'token' => $token,
            'identifier' => $identifier,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'identifier' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $resetRecord = DB::table('student_password_resets')
            ->where('student_identifier', $request->identifier)
            ->first();

        if (!$resetRecord ||
            !Hash::check($request->token, $resetRecord->token) ||
            Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['token' => 'Link reset tidak valid atau sudah kadaluarsa.']);
        }

        // Update password
        $student = Student::where('student_identifier', $request->identifier)->first();
        if ($student) {
            $student->password = Hash::make($request->password);
            $student->save();

            // Hapus token
         DB::table('student_password_resets')
                ->where('student_identifier', $request->identifier)
                ->delete();

            return redirect()->route('student.login')
                ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
        }

        return back()->withErrors(['identifier' => 'Gagal mereset password.']);
    }
}