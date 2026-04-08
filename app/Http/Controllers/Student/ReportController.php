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
        $studentIdentifier = auth('student')->user()->student_identifier;

        if ($rating->student_identifier !== $studentIdentifier) {
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
            $studentIdentifier = auth('student')->user()->student_identifier;

            $data = $request->validated();

            $priorityMap = [
                'technical' => 'high',
                'facility' => 'medium',
                'network' => 'critical',
                'other' => 'low'
            ];
            $data['priority'] = $priorityMap[$data['category']] ?? 'medium';

            $report = $this->service->createReport($data, $studentIdentifier);

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
            $studentIdentifier = auth('student')->user()->student_identifier;

            if ($report->student_identifier !== $studentIdentifier) {
                abort(403);
            }

            return view('student.reports.show', compact('report'));
        } catch (\Exception $e) {
            return redirect()->route('student.reports.history')->with('error', 'Laporan tidak ditemukan');
        }
    }

    public function history(Request $request)
    {
        $studentIdentifier = auth('student')->user()->student_identifier;

        return view('student.reports.history', [
            'reports' => $this->service->getUserReports($studentIdentifier, $request->only(['status', 'search']))
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
