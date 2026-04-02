<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Profile\UpdateProfileRequest;
use App\Services\Student\ProfileService;

class ProfileController extends Controller
{
    protected ProfileService $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function show()
    {
    try {
            $studentId = auth('student')->id();

            return view('student.profile.show', [
                'profile' => $this->service->getProfile($studentId),
                'stats' => $this->service->getStats($studentId)
            ]);
        } catch (\Exception $e) {
            return redirect()->route('student.dashboard')->with('error', 'Gagal memuat profil');
        }
    }

    public function edit()
    {
        try {
            $studentId = auth('student')->id();

            return view('student.profile.edit', [
                'profile' => $this->service->getProfile($studentId)
            ]);
        } catch (\Exception $e) {
            return redirect()->route('student.profile.show')->with('error', 'Gagal memuat form profil');
        }
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $studentId = auth('student')->id();

            $this->service->updateProfile($studentId, $request->validated());

            return redirect()->route('student.profile.show')->with('success', 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function sessions()
    {
        try {
            $studentId = auth('student')->id();

            return view('student.profile.sessions', [
                'sessions' => $this->service->getActiveSessions($studentId)
            ]);
        } catch (\Exception $e) {
            return redirect()->route('student.profile.show')->with('error', 'Gagal memuat sesi aktif');
        }
    }

    public function terminateSession($sessionId)
    {
        try {
            $studentId = auth('student')->id();

            $this->service->terminateSession($studentId, $sessionId);

            return response()->json(['success' => true, 'message' => 'Sesi berhasil diakhiri']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengakhiri sesi: ' . $e->getMessage()], 500);
        }
    }

    public function terminateAllSessions()
    {
        try {
            $studentId = auth('student')->id();

            $count = $this->service->terminateAllSessions($studentId);

            return response()->json(['success' => true, 'message' => "{$count} sesi berhasil diakhiri"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengakhiri semua sesi: ' . $e->getMessage()], 500);
        }
    }
}