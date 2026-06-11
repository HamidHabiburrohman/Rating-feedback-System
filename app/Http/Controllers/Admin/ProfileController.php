<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authentication\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        /** @var \App\Models\Authentication\Admin $admin */
        $admin = auth('admin')->user();
        $this->authorize('updateProfile', $admin);
        
        return view('admin.profile.edit', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\Authentication\Admin $admin */
        $admin = auth('admin')->user();
        $this->authorize('updateProfile', $admin);
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'timezone' => 'nullable|string|max:50',
        ]);
        
        try {
            $admin->update($request->only(['nama', 'phone', 'bio', 'timezone']));
            
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
        /** @var \App\Models\Authentication\Admin $admin */
        $admin = auth('admin')->user();
        $this->authorize('updateProfile', $admin);
        
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        if (!Hash::check($request->current_password, $admin->password)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password saat ini salah'
                ], 422);
            }
            
            return back()->withErrors([
                'current_password' => 'Password saat ini salah'
            ]);
        }
        
        try {
            $admin->password = Hash::make($request->password);
            $admin->save();
            
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
                    'message' => 'Gagal memperbarui password: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal memperbarui password: ' . $e->getMessage());
        }
    }

    public function updatePhoto(Request $request)
    {
        /** @var \App\Models\Authentication\Admin $admin */
        $admin = auth('admin')->user();
        $this->authorize('updateProfile', $admin);
        
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        try {
            $file = $request->file('photo');
            $path = $file->store('admin/photos', 'public');
            
            if ($admin->photo && Storage::disk('public')->exists($admin->photo)) {
                Storage::disk('public')->delete($admin->photo);
            }
            
            $admin->photo = $path;
            $admin->save();
            
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
        /** @var \App\Models\Authentication\Admin $admin */
        $admin = auth('admin')->user();
        $this->authorize('updateProfile', $admin);
        
        try {
            if ($admin->photo && Storage::disk('public')->exists($admin->photo)) {
                Storage::disk('public')->delete($admin->photo);
            }
            
            $admin->photo = null;
            $admin->save();
            
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

    public function updatePreferences(Request $request)
    {
        /** @var \App\Models\Authentication\Admin $admin */
        $admin = auth('admin')->user();
        $this->authorize('updateProfile', $admin);
        
        $request->validate([
            'preferences' => 'required|array',
        ]);
        
        try {
            $admin->mergePreferences($request->preferences);
            
            return response()->json([
                'success' => true,
                'message' => 'Preferensi berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui preferensi: ' . $e->getMessage()
            ], 500);
        }
    }
}