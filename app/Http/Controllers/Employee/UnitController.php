<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Http\Requests\Employee\Unit\UpdateUnitRequest;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    public function index()
    {
        $units = Auth::user()->units()->paginate(10);
        return view('employee.units.index', compact('units'));
    }

    public function show(Unit $unit)
    {
        $this->authorize('view', $unit);
        return view('employee.units.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        $this->authorize('update', $unit);
        return view('employee.units.edit', compact('unit'));
    }

    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $this->authorize('update', $unit);
        $unit->update($request->validated());
        return redirect()->route('employee.units.show', $unit)->with('success', 'Unit updated successfully.');
    }
}
