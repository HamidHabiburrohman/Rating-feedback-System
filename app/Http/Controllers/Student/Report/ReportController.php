<?php

namespace App\Http\Controllers\Student\Report;

use App\Http\Controllers\Controller;
use App\Services\Student\ReportService;
use App\Models\Feedback\Rating;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportService $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    public function create(Rating $rating)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            if (!$this->service->canReport($rating)) {
                $message = $this->service->getReportStatusMessage($rating);
                return redirect()->route('student.ratings.show', $rating->tracking_code)
                    ->with('error', $message ?: 'Anda tidak dapat melaporkan rating ini');
            }
            
            $categories = \App\Models\Report\ReportCategory::where('is_active', true)->get();
            
            return view('student.reports.create', compact('rating', 'categories'));
        } catch (\Exception $e) {
            return redirect()->route('student.ratings.history')
                ->with('error', 'Gagal memuat form laporan: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        $request->validate([
            'rating_id' => 'required|exists:ratings,id',
            'category' => 'required|string|exists:report_categories,slug',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'priority' => 'nullable|in:low,medium,high,critical',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);
        
        try {
            $data = $request->except('attachment');
            
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $path = $file->store('reports/attachments', 'public');
                $data['attachment_path'] = $path;
                $data['attachment_original_name'] = $file->getClientOriginalName();
                $data['attachment_mime_type'] = $file->getMimeType();
                $data['attachment_size'] = $file->getSize();
            }
            
            $report = $this->service->createReport($data, $student->id);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil dikirim',
                    'tracking_code' => $report->tracking_code,
                    'redirect' => route('student.reports.show', $report->tracking_code)
                ]);
            }
            
            return redirect()->route('student.reports.show', $report->tracking_code)
                ->with('success', 'Laporan berhasil dikirim');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim laporan: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withInput()
                ->with('error', 'Gagal mengirim laporan: ' . $e->getMessage());
        }
    }

    public function show(string $trackingCode)
    {
        try {
            $report = $this->service->findByTrackingCode($trackingCode);
            return view('student.reports.show', compact('report'));
        } catch (\Exception $e) {
            return redirect()->route('student.reports.history')
                ->with('error', 'Laporan tidak ditemukan');
        }
    }

    public function update(Request $request, string $trackingCode)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
        ]);
        
        try {
            $report = $this->service->findByTrackingCode($trackingCode);
            
            if ($report->student_id !== $student->id) {
                throw new \Exception('Anda tidak memiliki akses ke laporan ini');
            }
            
            if (!in_array($report->status, ['new', 'in_progress'])) {
                throw new \Exception('Laporan ini tidak dapat diedit');
            }
            
            $report->update($request->only(['title', 'description']));
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil diperbarui',
                    'redirect' => route('student.reports.show', $trackingCode)
                ]);
            }
            
            return redirect()->route('student.reports.show', $trackingCode)
                ->with('success', 'Laporan berhasil diperbarui');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui laporan: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withInput()
                ->with('error', 'Gagal memperbarui laporan: ' . $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            $filters = $request->only(['status', 'search', 'per_page']);
            $reports = $this->service->getUserReports($student->id, $filters);
            $stats = $this->service->getReportStats($student->id);
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('student.reports.partials.list', compact('reports'))->render(),
                    'pagination' => view('student.reports.partials.pagination', ['paginator' => $reports])->render(),
                    'stats' => $stats
                ]);
            }
            
            return view('student.reports.history', compact('reports', 'stats'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat riwayat laporan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('student.dashboard.index')
                ->with('error', 'Gagal memuat riwayat laporan: ' . $e->getMessage());
        }
    }

    public function checkCanReport(Rating $rating)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            $canReport = $this->service->canReport($rating);
            $message = $this->service->getReportStatusMessage($rating);
            
            return response()->json([
                'success' => true,
                'can_report' => $canReport,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengecek status: ' . $e->getMessage()
            ], 500);
        }
    }
}