<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Facility\StoreFacilityRequest;
use App\Http\Requests\Admin\Facility\UpdateFacilityRequest;
use App\Services\Admin\FacilityService;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    protected FacilityService $service;

    public function __construct(FacilityService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'sort', 'order', 'per_page']);
        $facilities = $this->service->getPaginated($filters);
        $stats = $this->service->getStats();
        $popular = $this->service->getPopularFacilities(5);

        return view('admin.facilities.index', compact('facilities', 'stats', 'popular'));
    }

    public function create()
    {
        return view('admin.facilities.create', ['icons' => $this->service->getAvailableIcons()]);
    }

    public function store(StoreFacilityRequest $request)
    {
        try {
            $this->service->create($request->validated());
            return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil dibuat');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal membuat fasilitas: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $facility = $this->service->find($id);
            $units = $facility->units()->select('units.id', 'units.name', 'units.code')->paginate(10);
            return view('admin.facilities.show', compact('facility', 'units'));
        } catch (\Exception $e) {
            return redirect()->route('admin.facilities.index')->with('error', 'Fasilitas tidak ditemukan');
        }
    }

    public function edit($id)
    {
        try {
            return view('admin.facilities.edit', [
                'facility' => $this->service->find($id),
                'icons' => $this->service->getAvailableIcons()
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.facilities.index')->with('error', 'Fasilitas tidak ditemukan');
        }
    }

    public function update(UpdateFacilityRequest $request, $id)
    {
        try {
            $this->service->update($id, $request->validated());
            return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui fasilitas: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return redirect()->route('admin.facilities.index')->with('success', 'Fasilitas berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.facilities.index')->with('error', $e->getMessage());
        }
    }

    public function getIcons()
    {
        try {
            return response()->json(['success' => true, 'data' => $this->service->getAvailableIcons()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil daftar icon'], 500);
        }
    }

    public function stats()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'overall' => $this->service->getStats(),
                    'popular' => $this->service->getPopularFacilities(10)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil statistik'], 500);
        }
    }

    public function units($id)
    {
        try {
            return response()->json(['success' => true, 'data' => $this->service->getUnits($id)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengambil data unit'], 500);
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