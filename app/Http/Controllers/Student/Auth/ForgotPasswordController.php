<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\StudentResetPasswordMail;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.student.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
        ]);

        $input = $request->input('email');

        // Cari berdasarkan NIM atau email
        $student = Student::where('student_identifier', $input)
            ->orWhere('email', $input)
            ->first();

        if (!$student || !$student->email) {
            return back()->withErrors([
                'email' => 'Akun tidak ditemukan atau tidak memiliki email terdaftar.',
            ])->withInput();
        }

        // Generate token
        $token = Str::random(64);

        // Simpan ke tabel student_password_resets
        DB::table('student_password_resets')->updateOrInsert(
            ['student_identifier' => $student->student_identifier],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        // Kirim email
        $resetUrl = route('student.password.reset', [
            'token' => $token,
            'identifier' => $student->student_identifier,
        ]);

        Mail::to($student->email)->send(new StudentResetPasswordMail($resetUrl, $student->name));

        return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
    }
}
