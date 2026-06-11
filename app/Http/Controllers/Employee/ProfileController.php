<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\Employee\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected ProfileService $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function edit()
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        try {
            $profile = $this->service->getProfile($employee->id);
            return view('employee.profile.edit', compact('profile'));
        } catch (\Exception $e) {
            return redirect()->route('employee.dashboard.index')
                ->with('error', 'Gagal memuat profil: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
        ]);
        
        try {
            $this->service->updateProfile($employee->id, $request->only(['name', 'phone', 'bio']));
            
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
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        try {
            $this->service->updatePassword(
                $employee->id,
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

    public function updatePhoto(Request $request)
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        try {
            $path = $this->service->updatePhoto($employee->id, $request->file('photo'));
            
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui',
                'photo_url' => asset('storage/' . $path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui foto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removePhoto()
    {
        /** @var \App\Models\Authentication\Employee $employee */
        $employee = auth('employee')->user();
        
        try {
            $this->service->removePhoto($employee->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus foto: ' . $e->getMessage()
            ], 500);
        }
    }
}