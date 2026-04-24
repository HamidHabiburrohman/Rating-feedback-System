<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Profile\UpdateProfileRequest;
use App\Services\Student\ProfileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class ProfileController extends Controller
{
    protected ProfileService $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function show()
    {
        $student = Auth::guard('student')->user();
        
        if (!$student) {
            return redirect()->route('student.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $studentIdentifier = $student->student_identifier;
        
        $profile = $this->service->getProfile($studentIdentifier);
        $stats = $this->service->getStats($studentIdentifier);
        $activities = $this->service->getRecentActivities($studentIdentifier);
        $weeklyEngagement = $this->service->getWeeklyEngagement($studentIdentifier);
        
        $score = $stats['average_rating'] * 20;
        $resolvedCount = $stats['total_reports'];

        $activitiesCollection = collect($activities);
        $recentActivities = $activitiesCollection->take(3);
        $totalActivities = $activitiesCollection->count();

        return view('student.profile.show', [
            'profile' => $profile,
            'stats' => $stats,
            'score' => $score,
            'resolvedCount' => $resolvedCount,
            'recentActivities' => $recentActivities,
            'totalActivities' => $totalActivities,
            'weeklyEngagement' => $weeklyEngagement,
        ]);
    }

    public function edit()
    {
        $student = Auth::guard('student')->user();
        
        if (!$student) {
            return redirect()->route('student.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $profile = $this->service->getProfile($student->student_identifier);
        
        return view('student.profile.edit', [
            'profile' => $profile
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $student = Auth::guard('student')->user();
        
        if (!$student) {
            return redirect()->route('student.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $this->service->updateProfile($student->student_identifier, $request->validated(), $request->file('photo'));

        return redirect()->route('student.profile.show')->with('success', 'Profil berhasil diperbarui');
    }

    public function sessions()
    {
        $student = Auth::guard('student')->user();
        
        if (!$student) {
            return redirect()->route('student.login')->with('error', 'Silakan login terlebih dahulu');
        }

        $sessions = $this->service->getActiveSessions($student->student_identifier);

        return view('student.profile.sessions', [
            'sessions' => $sessions
        ]);
    }

    public function terminateSession(int $sessionId)
    {
        $student = Auth::guard('student')->user();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            $this->service->terminateSession($student->student_identifier, $sessionId);
            return response()->json(['success' => true, 'message' => 'Sesi berhasil diakhiri']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function terminateAllSessions()
    {
        $student = Auth::guard('student')->user();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            $count = $this->service->terminateAllSessions($student->student_identifier);
            return response()->json(['success' => true, 'message' => "{$count} sesi berhasil diakhiri"]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}