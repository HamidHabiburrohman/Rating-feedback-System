<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\FacilityService;
use App\Models\Unit\Facility;
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
        $this->authorize('viewAny', Facility::class);
        
        try {
            $facilities = $this->service->getAll($request->all());
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('admin.facilities.partials.rows', ['facilities' => $facilities])->render()
                ]);
            }
            
            return view('admin.facilities.index', compact('facilities'));
        } catch (\Exception $e) {
            return redirect()->route('admin.facilities.index')
                ->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $this->authorize('create', Facility::class);
        return view('admin.facilities.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Facility::class);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:facilities,name',
            'icon' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);
        
        try {
            $facility = $this->service->create($request->all());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Fasilitas berhasil ditambahkan',
                    'data' => $facility
                ]);
            }
            
            return redirect()->route('admin.facilities.index')
                ->with('success', 'Fasilitas berhasil ditambahkan');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->withInput()
                ->with('error', 'Gagal menambahkan: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        try {
            $facility = $this->service->findById($id);
            $this->authorize('view', $facility);
            return view('admin.facilities.show', compact('facility'));
        } catch (\Exception $e) {
            return redirect()->route('admin.facilities.index')
                ->with('error', 'Fasilitas tidak ditemukan');
        }
    }

    public function edit(int $id)
    {
        try {
            $facility = $this->service->findById($id);
            $this->authorize('update', $facility);
            return view('admin.facilities.edit', compact('facility'));
        } catch (\Exception $e) {
            return redirect()->route('admin.facilities.index')
                ->with('error', 'Fasilitas tidak ditemukan');
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $facility = Facility::findOrFail($id);
            $this->authorize('update', $facility);
            
            $request->validate([
                'name' => 'required|string|max:255|unique:facilities,name,' . $id,
                'icon' => 'nullable|string|max:100',
                'description' => 'nullable|string|max:1000',
                'is_active' => 'boolean',
            ]);
            
            $this->service->update($id, $request->all());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Fasilitas berhasil diperbarui'
                ]);
            }
            
            return redirect()->route('admin.facilities.index')
                ->with('success', 'Fasilitas berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->withInput()
                ->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, int $id)
    {
        try {
            $facility = Facility::findOrFail($id);
            $this->authorize('delete', $facility);
            
            $this->service->delete($id);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Fasilitas berhasil dihapus'
                ]);
            }
            
            return redirect()->route('admin.facilities.index')
                ->with('success', 'Fasilitas berhasil dihapus');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function popular()
    {
        $this->authorize('viewAny', Facility::class);
        
        try {
            return response()->json([
                'success' => true,
                'data' => $this->service->getPopular(10)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data populer'
            ], 500);
        }
    }
}