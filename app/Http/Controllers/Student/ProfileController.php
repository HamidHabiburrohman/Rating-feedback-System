<?php
// app/Http/Controllers/Student/ProfileController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Profile\UpdateProfileRequest;
use App\Services\Student\ProfileService;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    protected ProfileService $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function show()
    {
        Log::info('[PROFILE] === SHOW METHOD CALLED ===');
        
        try {
            $studentId = auth('student')->id();
            Log::info('[PROFILE] Student ID from auth: ' . ($studentId ?? 'null'));
            
            $user = auth('student')->user();
            Log::info('[PROFILE] Auth check - student() user: ' . json_encode($user ? [
                'id' => $user->id,
                'student_identifier' => $user->student_identifier,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ] : 'null'));

            if (!$studentId) {
                Log::warning('[PROFILE] No student ID found, redirecting to login');
                return redirect()->route('student.login')->with('error', 'Silakan login terlebih dahulu');
            }

            Log::info('[PROFILE] Calling service->getProfile with ID: ' . $studentId);
            $profile = $this->service->getProfile($studentId);
            Log::info('[PROFILE] getProfile result: ' . ($profile ? 'SUCCESS - name: ' . $profile->name : 'FAILED - null'));
            
            Log::info('[PROFILE] Calling service->getStats with ID: ' . $studentId);
            $stats = $this->service->getStats($studentId);
            Log::info('[PROFILE] getStats result: ' . json_encode($stats));

            Log::info('[PROFILE] Rendering student.profile.show view');
            return view('student.profile.show', [
                'profile' => $profile,
                'stats' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('[PROFILE] ERROR in show(): ' . $e->getMessage());
            Log::error('[PROFILE] Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('student.login')->with('error', 'Gagal memuat profil: ' . $e->getMessage());
        }
    }

    public function edit()
    {
        Log::info('[PROFILE] === EDIT METHOD CALLED ===');
        
        try {
            $studentId = auth('student')->id();
            Log::info('[PROFILE] Student ID for edit: ' . ($studentId ?? 'null'));

            if (!$studentId) {
                Log::warning('[PROFILE] No student ID for edit, redirecting to login');
                return redirect()->route('student.login')->with('error', 'Silakan login terlebih dahulu');
            }

            Log::info('[PROFILE] Calling service->getProfile for edit');
            $profile = $this->service->getProfile($studentId);
            
            $profileData = [
                'id' => $profile->id,
                'student_identifier' => $profile->student_identifier,
                'name' => $profile->name,
                'email' => $profile->email,
                'major' => $profile->major,
                'class_year' => $profile->class_year,
                'bio' => $profile->bio,
                'phone' => $profile->phone,
                'location' => $profile->location,
                'portfolio_url' => $profile->portfolio_url,
                'linkedin_url' => $profile->linkedin_url,
                'photo' => $profile->photo,
                'photo_url' => $profile->photo_url,
                'created_at' => $profile->created_at,
                'updated_at' => $profile->updated_at,
            ];
            Log::info('[PROFILE] Edit profile data: ' . json_encode($profileData));

            Log::info('[PROFILE] Rendering student.profile.edit view');
            return view('student.profile.edit', [
                'profile' => $profile
            ]);
            
        } catch (\Exception $e) {
            Log::error('[PROFILE] ERROR in edit(): ' . $e->getMessage());
            Log::error('[PROFILE] Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('student.profile.show')->with('error', 'Gagal memuat form profil');
        }
    }

    public function update(UpdateProfileRequest $request)
    {
        Log::info('[PROFILE] === UPDATE METHOD CALLED ===');
        Log::info('[PROFILE] Request data: ' . json_encode($request->validated()));
        
        try {
            $studentId = auth('student')->id();
            Log::info('[PROFILE] Student ID for update: ' . ($studentId ?? 'null'));

            Log::info('[PROFILE] Calling service->updateProfile');
            $this->service->updateProfile($studentId, $request->validated(), $request->file('photo'));
            Log::info('[PROFILE] Update successful');

            return redirect()->route('student.profile.show')->with('success', 'Profil berhasil diperbarui');
            
        } catch (\Exception $e) {
            Log::error('[PROFILE] ERROR in update(): ' . $e->getMessage());
            Log::error('[PROFILE] Stack trace: ' . $e->getTraceAsString());
            return back()->withInput()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function sessions()
    {
        Log::info('[PROFILE] === SESSIONS METHOD CALLED ===');
        
        try {
            $studentId = auth('student')->id();
            Log::info('[PROFILE] Student ID for sessions: ' . ($studentId ?? 'null'));

            if (!$studentId) {
                Log::warning('[PROFILE] No student ID for sessions, redirecting to login');
                return redirect()->route('student.login')->with('error', 'Silakan login terlebih dahulu');
            }

            Log::info('[PROFILE] Calling service->getActiveSessions');
            $sessions = $this->service->getActiveSessions($studentId);
            Log::info('[PROFILE] Sessions count: ' . count($sessions));
            Log::info('[PROFILE] Sessions data: ' . json_encode($sessions));

            Log::info('[PROFILE] Rendering student.profile.sessions view');
            return view('student.profile.sessions', [
                'sessions' => $sessions
            ]);
            
        } catch (\Exception $e) {
            Log::error('[PROFILE] ERROR in sessions(): ' . $e->getMessage());
            Log::error('[PROFILE] Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('student.profile.show')->with('error', 'Gagal memuat sesi aktif');
        }
    }

    public function terminateSession(int $sessionId)
    {
        Log::info('[PROFILE] === TERMINATE SESSION METHOD CALLED ===');
        Log::info('[PROFILE] Session ID to terminate: ' . $sessionId);
        
        try {
            $studentId = auth('student')->id();
            Log::info('[PROFILE] Student ID: ' . ($studentId ?? 'null'));

            $this->service->terminateSession($studentId, $sessionId);
            Log::info('[PROFILE] Session termination successful');

            return response()->json(['success' => true, 'message' => 'Sesi berhasil diakhiri']);
            
        } catch (\Exception $e) {
            Log::error('[PROFILE] ERROR in terminateSession(): ' . $e->getMessage());
            Log::error('[PROFILE] Stack trace: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Gagal mengakhiri sesi: ' . $e->getMessage()], 500);
        }
    }

    public function terminateAllSessions()
    {
        Log::info('[PROFILE] === TERMINATE ALL SESSIONS METHOD CALLED ===');
        
        try {
            $studentId = auth('student')->id();
            Log::info('[PROFILE] Student ID: ' . ($studentId ?? 'null'));

            $count = $this->service->terminateAllSessions($studentId);
            Log::info('[PROFILE] Terminated ' . $count . ' sessions');

            return response()->json(['success' => true, 'message' => "{$count} sesi berhasil diakhiri"]);
            
        } catch (\Exception $e) {
            Log::error('[PROFILE] ERROR in terminateAllSessions(): ' . $e->getMessage());
            Log::error('[PROFILE] Stack trace: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Gagal mengakhiri semua sesi: ' . $e->getMessage()], 500);
        }
    }
}