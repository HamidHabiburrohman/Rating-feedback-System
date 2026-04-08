<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Unit\UnitRequest;
use App\Services\Admin\UnitService;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    protected UnitService $service;

    public function __construct(UnitService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'type', 'sort', 'order', 'per_page', 'with_trashed', 'only_trashed']);
        $units = $this->service->getPaginated($filters);
        $typeNames = $this->service->getTypeNames();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.units.partials.rows', compact('units'))->render(),
                'pagination' => view('admin.units.partials.pagination', compact('units'))->render()
            ]);
        }

        return view('admin.units.index', compact('units', 'typeNames'));
    }

    public function trashed(Request $request)
    {
        $filters = $request->only(['search', 'sort', 'order', 'per_page']);
        $units = $this->service->getTrashed($filters);

        return view('admin.units.trashed', compact('units'));
    }

    public function create()
    {
        return view('admin.units.create', $this->service->getCreateData());
    }

    public function store(UnitRequest $request)
    {
        try {
            $unit = $this->service->create($request->validated());

            // Redirect ke edit agar user bisa langsung upload foto via AJAX
            return redirect()
                ->route('admin.units.edit', $unit->id)
                ->with('success', 'Unit berhasil ditambahkan. Anda dapat menambahkan foto sekarang.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan unit: ' . $e->getMessage())->withInput();
        }
    }

    public function show(string|int $id)
    {
        $unit = $this->service->findWithTrashed($id);

        if (!$unit) {
            return redirect()->route('admin.units.index')->with('error', 'Unit tidak ditemukan');
        }

        return view('admin.units.show', compact('unit'));
    }

    public function edit(string|int $id)
    {
        try {
            return view('admin.units.edit', $this->service->getEditData($id));
        } catch (\Exception $e) {
            return redirect()->route('admin.units.index')->with('error', 'Unit tidak ditemukan');
        }
    }

    public function update(UnitRequest $request, string|int $id)
    {
        try {
            $this->service->update($id, $request->validated());
            return redirect()->route('admin.units.index')->with('success', 'Unit berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui unit: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Unit $unit)
    {
        try {
            $this->service->delete($unit->id);
            return redirect()->route('admin.units.index')->with('success', 'Unit berhasil dipindahkan ke trash');
        } catch (\Exception $e) {
            return redirect()->route('admin.units.index')->with('error', $e->getMessage());
        }
    }

    public function restore(string|int $id)
    {
        try {
            $this->service->restore($id);
            return redirect()->route('admin.units.index')->with('success', 'Unit berhasil dipulihkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memulihkan unit: ' . $e->getMessage());
        }
    }

    public function forceDelete(string|int $id)
    {
        try {
            $this->service->forceDelete($id);
            return redirect()->route('admin.units.trash')->with('success', 'Unit berhasil dihapus permanen');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus unit permanen: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Request $request, string|int $id)
    {
        try {
            $unit = $this->service->find($id);
            $unit->is_active = !$unit->is_active;
            $unit->save();

            return response()->json([
                'success' => true,
                'message' => 'Status unit berhasil diubah',
                'status' => $unit->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function syncFacilities(Request $request, string|int $id)
    {
        try {
            $unit = $this->service->find($id);
            $unit->facilities()->sync($request->facilities ?? []);

            return response()->json([
                'success' => true,
                'message' => 'Fasilitas berhasil disinkronkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal sinkronisasi fasilitas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            $count = 0;

            foreach ($ids as $id) {
                try {
                    $this->service->delete($id);
                    $count++;
                } catch (\Exception $e) {
                    continue;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "{$count} unit berhasil dipindahkan ke trash"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus unit: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkRestore(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            $count = 0;

            foreach ($ids as $id) {
                try {
                    $this->service->restore($id);
                    $count++;
                } catch (\Exception $e) {
                    continue;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "{$count} unit berhasil dipulihkan"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulihkan unit: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkForceDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            $count = 0;

            foreach ($ids as $id) {
                try {
                    $this->service->forceDelete($id);
                    $count++;
                } catch (\Exception $e) {
                    continue;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "{$count} unit berhasil dihapus permanen"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus unit permanen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkActivate(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            $status = $request->input('status', true);

            $count = Unit::whereIn('id', $ids)->update(['is_active' => $status]);

            return response()->json([
                'success' => true,
                'message' => "{$count} unit berhasil " . ($status ? 'diaktifkan' : 'dinonaktifkan')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status unit: ' . $e->getMessage()
            ], 500);
        }
    }
}