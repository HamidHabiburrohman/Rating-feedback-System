<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UnitPhoto\UploadUnitPhotoRequest;
use App\Http\Requests\Admin\UnitPhoto\SetPrimaryPhotoRequest;
use App\Http\Requests\Admin\UnitPhoto\ReorderPhotoRequest;
use App\Services\Admin\UnitPhotoService;
use App\Models\Unit;
use App\Models\UnitPhoto;

class UnitPhotoController extends Controller
{
    protected UnitPhotoService $service;

    public function __construct(UnitPhotoService $service)
    {
        $this->service = $service;
    }

    public function index(Unit $unit)
    {
        return response()->json([
            'success' => true,
            'data' => $unit->photos()->orderBy('sort_order')->get()
        ]);
    }

    public function upload(UploadUnitPhotoRequest $request, Unit $unit)
    {
        $result = $this->service->upload($unit, $request->file('photos'), $request->input('is_primary'));

        if (is_array($result) && isset($result['success']) && $result['success'] === true) {
            return response()->json($result, 200);
        }

        return response()->json($result, 422);
    }

    public function setPrimary(Unit $unit, UnitPhoto $photo)
    {
        $result = $this->service->setPrimary($unit, $photo->id);

        if (is_array($result) && isset($result['success']) && $result['success'] === true) {
            return response()->json($result, 200);
        }

        return response()->json($result, 422);
    }

    public function reorder(Unit $unit, ReorderPhotoRequest $request)
    {
        $result = $this->service->reorder($unit, $request->photos);

        if (is_array($result) && isset($result['success']) && $result['success'] === true) {
            return response()->json($result, 200);
        }

        return response()->json($result, 422);
    }

    public function destroy(Unit $unit, UnitPhoto $photo)
    {
        if ($photo->unit_id !== $unit->id) {
            return response()->json([
                'success' => false,
                'message' => 'Photo does not belong to this unit'
            ], 403);
        }

        $result = $this->service->deletePhoto($photo);

        if (is_array($result) && isset($result['success']) && $result['success'] === true) {
            return response()->json($result, 200);
        }

        return response()->json($result, 422);
    }
}
