<?php

namespace App\Services\Student\Auth;

use App\Models\Student;
use App\Models\StudentSession;
use App\Services\Student\BaseStudentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService extends BaseStudentService
{
    protected Student $student;
    protected StudentSession $studentSession;

    public function __construct(Student $student, StudentSession $studentSession)
    {
        $this->student = $student;
        $this->studentSession = $studentSession;
    }

    public function login(string $identifier, string $password, ?string $ip, ?string $userAgent): Student
    {
        try {
            $student = $this->student
                ->where('student_identifier', $identifier)
                ->orWhere('email', $identifier)
                ->first();

            if (!$student) {
                throw new \Exception('Nomor induk atau email tidak ditemukan');
            }

            if (!Hash::check($password, $student->password)) {
                throw new \Exception('Password yang Anda masukkan salah');
            }

            // Gunakan guard 'student' untuk login
            Auth::guard('student')->login($student);

            $this->createSession($student->id, $ip, $userAgent);

            Log::info('Student logged in', [
                'student_id' => $student->id,
                'identifier' => $identifier,
                'ip' => $ip
            ]);

            return $student;
        } catch (\Exception $e) {
            Log::error('Login failed', [
                'identifier' => $identifier,
                'ip' => $ip,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function logout(): void
    {
        try {
            $student = Auth::guard('student')->user();

            if ($student) {
                $this->endSession($student->id);
            }

            Auth::guard('student')->logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();

            Log::info('Student logged out', [
                'student_id' => $student->id ?? null
            ]);
        } catch (\Exception $e) {
            Log::error('Logout failed', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function check(): bool
    {
        return Auth::guard('student')->check();
    }

    public function getAuthenticatedStudent(): ?Student
    {
        $student = Auth::guard('student')->user();

        if ($student instanceof Student) {
            return $student;
        }

        return null;
    }

    protected function createSession(int $studentId, ?string $ip, ?string $userAgent): void
    {
        // Hapus session lama jika ada
        $this->studentSession
            ->where('student_id', $studentId)
            ->delete();
            
        // Buat session baru
        $this->studentSession->create([
            'student_id' => $studentId,
            'session_token' => session()->getId(),
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'last_activity_at' => now()
        ]);
    }

    protected function endSession(int $studentId): void
    {
        $this->studentSession
            ->where('student_id', $studentId)
            ->where('session_token', session()->getId())
            ->delete();
    }
}