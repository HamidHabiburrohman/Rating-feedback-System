<?php
namespace App\Services\Student\Auth;

use App\Models\Authentication\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\Student\VerificationMail;
use App\Mail\Student\ResetPasswordMail;

class AuthService
{
    public function register(array $data): Student
    {
        return DB::transaction(function () use ($data) {
            $student = Student::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'student_identifier' => $this->generateUniqueIdentifier(),
                'is_active' => true,
                'is_verified' => false,
            ]);

            $this->sendVerificationEmail($student);
            return $student;
        });
    }

    public function login(array $credentials, bool $remember = false): ?Student
    {
        if (Auth::guard('student')->attempt($credentials, $remember)) {
            $student = Auth::guard('student')->user();

            if (!$student->is_active) {
                Auth::guard('student')->logout();
                throw new \Exception('Akun Anda telah dinonaktifkan.');
            }

            if (!$student->is_verified) {
                Auth::guard('student')->logout();
                throw new \Exception('Silakan verifikasi email Anda terlebih dahulu.');
            }

            return $student;
        }
        return null;
    }

    public function logout(): void
    {
        Auth::guard('student')->logout();
    }

    public function verifyEmail(int $studentId): bool
    {
        $student = Student::findOrFail($studentId);
        if (!$student->is_verified) {
            $student->update(['is_verified' => true]);
        }
        return true;
    }

    public function requestPasswordReset(string $email): ?string
    {
        $student = Student::where('email', $email)->first();
        if (!$student) return null;
        if (!$student->is_active) throw new \Exception('Akun Anda telah dinonaktifkan.');

        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $student->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        $resetUrl = route('student.password.reset', ['token' => $token, 'email' => $student->email]);
        
        try {
            Mail::to($student->email)->send(new ResetPasswordMail($resetUrl, $student->name));
        } catch (\Exception $e) {
            Log::warning("Failed to send reset password email: " . $e->getMessage());
        }

        return $token;
    }

    public function resetPassword(string $email, string $token, string $newPassword): bool
    {
        $resetRecord = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$resetRecord || !Hash::check($token, $resetRecord->token) || now()->parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            return false;
        }

        $student = Student::where('email', $email)->first();
        if (!$student) return false;

        return DB::transaction(function () use ($student, $newPassword, $email) {
            $student->update(['password' => Hash::make($newPassword)]);
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return true;
        });
    }

    protected function generateUniqueIdentifier(): string
    {
        do {
            $identifier = 'STU-' . strtoupper(Str::random(8));
        } while (Student::where('student_identifier', $identifier)->exists());
        return $identifier;
    }

    protected function sendVerificationEmail(Student $student): void
    {
        try {
            $verificationUrl = URL::temporarySignedRoute(
                'student.verify.email',
                now()->addHours(24),
                ['id' => $student->id]
            );

            Mail::to($student->email)->send(new VerificationMail(
                $student->id,
                $student->name,
                $student->email,
                $verificationUrl
            ));
        } catch (\Exception $e) {
            Log::warning("Failed to send verification email to {$student->email}: " . $e->getMessage());
        }
    }
}