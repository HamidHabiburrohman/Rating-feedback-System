<?php

namespace App\Http\Controllers\Student\Profile;

use App\Http\Controllers\Controller;
use App\Services\Student\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected ProfileService $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function show()
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            $profile = $this->service->getProfile($student->id);
            $stats = $this->service->getStats($student->id);
            $recentActivities = $this->service->getRecentActivities($student->id);
            $weeklyEngagement = $this->service->getWeeklyEngagement($student->id);
            
            return view('student.profile.show', compact('profile', 'stats', 'recentActivities', 'weeklyEngagement'));
        } catch (\Exception $e) {
            return redirect()->route('student.dashboard.index')
                ->with('error', 'Gagal memuat profil: ' . $e->getMessage());
        }
    }

    public function edit()
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            $profile = $this->service->getProfile($student->id);
            return view('student.profile.edit', compact('profile'));
        } catch (\Exception $e) {
            return redirect()->route('student.profile.show')
                ->with('error', 'Gagal memuat profil: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
        ]);
        
        try {
            $this->service->updateProfile($student->id, $request->only(['name', 'phone', 'bio']));
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profil berhasil diperbarui'
                ]);
            }
            
            return back()->with('success', 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        try {
            $this->service->updatePassword(
                $student->id,
                $request->current_password,
                $request->password
            );
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password berhasil diperbarui'
                ]);
            }
            
            return back()->with('success', 'Password berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
            
            return back()->withErrors(['current_password' => $e->getMessage()]);
        }
    }

    public function sessions()
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            $sessions = $this->service->getSessions($student->id);
            
            return response()->json([
                'success' => true,
                'data' => $sessions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat sesi aktif: ' . $e->getMessage()
            ], 500);
        }
    }

    public function terminateSession(Request $request, string $sessionId)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            $this->service->terminateSession($student->id, $sessionId);
            
            return response()->json([
                'success' => true,
                'message' => 'Sesi berhasil dihentikan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghentikan sesi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function terminateAllSessions()
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            $count = $this->service->terminateAllSessions($student->id);
            
            return response()->json([
                'success' => true,
                'message' => "{$count} sesi berhasil dihentikan"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghentikan sesi: ' . $e->getMessage()
            ], 500);
        }
    }
}