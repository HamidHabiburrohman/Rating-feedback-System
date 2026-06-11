<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\UnitPhotoService;
use App\Models\Unit\Unit;
use App\Models\Unit\UnitPhoto;
use Illuminate\Http\Request;

class UnitPhotoController extends Controller
{
    protected UnitPhotoService $service;

    public function __construct(UnitPhotoService $service)
    {
        $this->service = $service;
    }

    public function index(int $unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $this->authorize('view', $unit);
        
        try {
            $photos = $this->service->getByUnit($unitId);
            return view('admin.units.photos.index', compact('unit', 'photos'));
        } catch (\Exception $e) {
            return redirect()->route('admin.units.show', $unitId)
                ->with('error', 'Gagal memuat foto: ' . $e->getMessage());
        }
    }

    public function upload(Request $request, int $unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $this->authorize('update', $unit);
        
        $request->validate([
            'photos' => 'required|array|max:10',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);
        
        try {
            $photos = $this->service->uploadMultiple($unitId, $request->file('photos'));
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => count($photos) . ' foto berhasil diupload',
                    'data' => $photos
                ]);
            }
            
            return back()->with('success', count($photos) . ' foto berhasil diupload');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal upload foto: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal upload foto: ' . $e->getMessage());
        }
    }

    public function setPrimary(Request $request, int $unitId, int $photoId)
    {
        $unit = Unit::findOrFail($unitId);
        $this->authorize('update', $unit);
        
        try {
            $this->service->setPrimary($photoId, $unitId);
            
            return response()->json([
                'success' => true,
                'message' => 'Foto utama berhasil diubah'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah foto utama: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reorder(Request $request, int $unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $this->authorize('update', $unit);
        
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:unit_photos,id',
            'orders.*.sort_order' => 'required|integer|min:0',
        ]);
        
        try {
            $this->service->reorder($unitId, $request->orders);
            
            return response()->json([
                'success' => true,
                'message' => 'Urutan foto berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengurutkan foto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, int $unitId, int $photoId)
    {
        $unit = Unit::findOrFail($unitId);
        $this->authorize('update', $unit);
        
        try {
            $this->service->delete($photoId, $unitId);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Foto berhasil dihapus'
                ]);
            }
            
            return back()->with('success', 'Foto berhasil dihapus');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus foto: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Gagal menghapus foto: ' . $e->getMessage());
        }
    }
}