<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\UpdateProfileRequest;
use App\Http\Requests\Admin\Profile\UpdatePasswordRequest;
use App\Models\Admin;
use App\Services\Admin\AdminProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminProfileController extends Controller
{
    protected AdminProfileService $service;

    public function __construct(AdminProfileService $service)
    {
        $this->service = $service;
    }

    public function show()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.show', compact('admin'));
    }

    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.edit', compact('admin'));
    }

    public function update(UpdateProfileRequest $request)
    {
        
        try {
            $admin = Auth::guard('admin')->user();
            $this->service->updateProfile($admin, $request->validated());

            if ($request->has('profile_banner')) {
                $admin->setPreference('profile_banner', $request->profile_banner);
            }

            return redirect()->route('admin.profile.show')
                ->with('success', 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $admin = Auth::guard('admin')->user();
            $this->service->updatePassword($admin, $request->validated());

            return redirect()->route('admin.profile.show')
                ->with('success', 'Password berhasil diubah');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengubah password: ' . $e->getMessage());
        }
    }

    public function updatePhoto(Request $request)
    {
        Log::info('=== CONTROLLER: updatePhoto called ===');
        Log::info('Request method: ' . $request->method());
        Log::info('Has file: ' . ($request->hasFile('photo') ? 'YES' : 'NO'));
        Log::info('Content type: ' . $request->header('Content-Type'));

        $request->validate([
            'photo' => 'required|image|max:2048'
        ]);

        try {
            $admin = Auth::guard('admin')->user();
            Log::info('Admin from auth: ' . $admin->email);

            $this->service->updatePhoto($admin, $request->file('photo'));

            $fresh = Admin::find($admin->id);
            Log::info('Controller - photo_url: ' . $fresh->photo_url);

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui',
                'photo_url' => $fresh->photo_url
            ]);
        } catch (\Exception $e) {
            Log::error('Controller - Upload failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function removePhoto()
    {
        try {
            $admin = Auth::guard('admin')->user();
            $this->service->removePhoto($admin);

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}