<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Report\StoreReportRequest;
use App\Services\Student\ReportService;
use Illuminate\Http\Request;
use App\Models\Rating;

class ReportController extends Controller
{
    protected ReportService $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    public function create(Rating $rating)
    {
        $studentId = auth('student')->id();

        if ($rating->student_id !== $studentId) {
            abort(403);
        }

        if (!$this->service->canReport($rating)) {
            return redirect()->route('student.ratings.show', $rating->tracking_code)
                ->with('error', 'Tidak dapat melaporkan rating ini');
        }

        return view('student.reports.create', compact('rating'));
    }

    public function store(StoreReportRequest $request)
    {
        try {
            $data = array_merge($request->validated(), [
                'student_id' => auth('student')->id()
            ]);

            $report = $this->service->create($data);

            return redirect()->route('student.reports.show', $report->tracking_code)
                ->with('success', 'Laporan berhasil dikirim');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal mengirim laporan: ' . $e->getMessage());
        }
    }

    public function show($trackingCode)
    {
        try {
            $report = $this->service->findByTrackingCode($trackingCode);

            if ($report->student_id !== auth('student')->id()) {
                abort(403);
            }

            return view('student.reports.show', compact('report'));
        } catch (\Exception $e) {
            return redirect()->route('student.reports.history')->with('error', 'Laporan tidak ditemukan');
        }
    }

    public function history(Request $request)
    {
        $studentId = auth('student')->id();

        return view('student.reports.history', [
            'reports' => $this->service->getUserReports($studentId, $request->only(['status', 'search']))
        ]);
    }

    public function checkCanReport(Rating $rating)
    {
        try {
            return response()->json([
                'success' => true,
                'can_report' => $this->service->canReport($rating),
                'message' => $this->service->getReportStatusMessage($rating)
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memeriksa status laporan'], 500);
        }
    }
}