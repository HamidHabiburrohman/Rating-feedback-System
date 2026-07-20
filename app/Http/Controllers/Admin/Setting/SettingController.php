<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Services\Admin\Setting\SettingService;
use App\Models\System\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected SettingService $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $this->authorize('viewAny', Setting::class);
        
        try {
            $settings = $this->service->getAll();
            $groups = $this->service->getGrouped();
            
            return view('admin.settings.index', compact('settings', 'groups'));
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Gagal memuat pengaturan');
        }
    }

    public function update(Request $request)
    {
        $this->authorize('update', Setting::class);
        
        $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string|max:5000',
        ]);
        
        try {
            $this->service->updateMany($request->settings, auth('admin')->id());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengaturan berhasil diperbarui'
                ]);
            }
            
            return back()->with('success', 'Pengaturan berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui pengaturan: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal memperbarui pengaturan: ' . $e->getMessage());
        }
    }
}