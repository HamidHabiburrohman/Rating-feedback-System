<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\StoreSettingRequest;
use App\Http\Requests\Admin\Setting\UpdateSettingRequest;
use App\Http\Requests\Admin\Setting\BulkUpdateSettingRequest;
use App\Services\Admin\SettingService;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected SettingService $service;

    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'group', 'is_public', 'sort', 'order', 'per_page']);
        $settings = $this->service->getPaginated($filters);
        $stats = $this->service->getStats();
        $formData = $this->service->getFormData();
        $groups = $this->service->getGroups();

        return view('admin.settings.index', compact('settings', 'stats', 'formData', 'groups'));
    }

    public function create()
    {
        return view('admin.settings.create', ['formData' => $this->service->getFormData()]);
    }

    public function store(StoreSettingRequest $request)
    {
        try {
            $this->service->create($request->validated());
            return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil dibuat');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal membuat pengaturan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            return view('admin.settings.show', ['setting' => $this->service->find($id)]);
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.index')->with('error', 'Pengaturan tidak ditemukan');
        }
    }

    public function edit($id)
    {
        try {
            return view('admin.settings.edit', [
                'setting' => $this->service->find($id),
                'formData' => $this->service->getFormData()
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.index')->with('error', 'Pengaturan tidak ditemukan');
        }
    }

    public function update(UpdateSettingRequest $request, $id)
    {
        try {
            $this->service->update($id, $request->validated());
            return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui pengaturan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.index')->with('error', 'Gagal menghapus pengaturan: ' . $e->getMessage());
        }
    }

    public function bulkUpdate(BulkUpdateSettingRequest $request)
    {
        try {
            return response()->json($this->service->bulkUpdate($request->settings));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui pengaturan: ' . $e->getMessage()], 500);
        }
    }

    public function getByGroup($group)
    {
        try {
            return response()->json(['success' => true, 'data' => $this->service->getSettingsByGroup($group)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil pengaturan grup'], 500);
        }
    }

    public function getPublic()
    {
        try {
            return response()->json(['success' => true, 'data' => $this->service->getPublicSettings()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil pengaturan publik'], 500);
        }
    }

    public function getValue($key, Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'key' => $key,
                    'value' => $this->service->getValue($key, $request->get('default'))
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil nilai pengaturan'], 500);
        }
    }

    public function stats()
    {
        try {
            return response()->json(['success' => true, 'data' => $this->service->getStats()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil statistik'], 500);
        }
    }

    public function resetToDefault($key)
    {
        try {
            return response()->json($this->service->resetToDefault($key));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mereset pengaturan: ' . $e->getMessage()], 500);
        }
    }

    public function import(Request $request)
    {
        try {
            $request->validate(['config' => 'required|array']);
            return response()->json($this->service->importFromConfig($request->config));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengimpor pengaturan: ' . $e->getMessage()], 500);
        }
    }

    public function export()
    {
        try {
            return $this->service->export();
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }
}