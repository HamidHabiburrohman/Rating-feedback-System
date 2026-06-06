<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $unitIds = Auth::user()->units()->pluck('units.id');
        $reports = Report::whereIn('unit_id', $unitIds)->with('user', 'unit')->paginate(15);

        return view('employee.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $this->authorize('view', $report);
        return view('employee.reports.show', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        $this->authorize('update', $report);
        $report->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Report status updated.');
    }
}
