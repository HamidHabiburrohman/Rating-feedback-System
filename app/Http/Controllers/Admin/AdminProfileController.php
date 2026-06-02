<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\UpdateProfileRequest;
use App\Http\Requests\Admin\Profile\UpdatePasswordRequest;
use App\Http\Requests\Admin\Profile\UpdatePreferencesRequest;
use App\Models\Admin;
use App\Services\Admin\AdminProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function updatePreferences(UpdatePreferencesRequest $request)
    {
        try {
            $admin = Auth::guard('admin')->user();

            $preferences = $request->only([
                'theme',
                'language',
                'notifications',
                'compact_sidebar',
                'show_activity',
                'login_notifications'
            ]);

            $preferences = array_filter($preferences, function ($value) {
                return !is_null($value);
            });

            $this->service->updatePreferences($admin, ['preferences' => $preferences]);

            return redirect()->route('admin.profile.show')
                ->with('success', 'Preferensi berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui preferensi: ' . $e->getMessage());
        }
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048'
        ]);

        try {
            $admin = Auth::guard('admin')->user();
            $this->service->updatePhoto($admin, $request->file('photo'));

            $fresh = Admin::find($admin->id);

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui',
                'photo_url' => $fresh->photo_url
            ]);
        } catch (\Exception $e) {
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
