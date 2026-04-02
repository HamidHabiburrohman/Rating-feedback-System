<?php

namespace App\Services\Admin;

use App\Models\Report;
use App\Models\Rating;
use App\Services\Admin\BaseAdminService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportManagementService extends BaseAdminService
{
    protected array $searchableColumns = ['title', 'description', 'tracking_code'];
    protected array $filterableColumns = ['status', 'priority', 'unit_id', 'student_id', 'admin_id'];
    protected string $defaultSort = 'created_at';
    protected string $defaultOrder = 'desc';

    public function __construct(Report $report)
    {
        $this->model = $report;
        parent::__construct();
    }

    public function getPaginatedReports(array $filters = []): LengthAwarePaginator
    {
        try {
            $query = $this->getBaseQuery($filters)
                ->with(['unit', 'student', 'rating', 'admin']);

            return $this->executePaginate($query, $filters);
        } catch (\Exception $e) {
            Log::error('ReportManagementService::getPaginatedReports error', [
                'message' => $e->getMessage(),
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);

            return new LengthAwarePaginator(
                collect([]),
                0,
                $filters['per_page'] ?? 10,
                1,
                ['path' => request()->url()]
            );
        }
    }

    public function getFilterData(): array
    {
        return [
            'statuses' => [
                '' => 'Semua Status',
                'new' => 'Baru',
                'in_progress' => 'Diproses',
                'replied' => 'Ditanggapi',
                'resolved' => 'Selesai',
                'rejected' => 'Ditolak'
            ],
            'priorities' => [
                '' => 'Semua Prioritas',
                'low' => 'Rendah',
                'medium' => 'Sedang',
                'high' => 'Tinggi',
                'critical' => 'Kritis'
            ],
            'units' => \App\Models\Unit::pluck('name', 'id')->toArray()
        ];
    }

    public function getStats(): array
    {
        return [
            'total' => $this->model->count(),
            'new' => $this->model->where('status', 'new')->count(),
            'in_progress' => $this->model->where('status', 'in_progress')->count(),
            'resolved' => $this->model->where('status', 'resolved')->count(),
            'rejected' => $this->model->where('status', 'rejected')->count(),
            'pending_preview' => $this->model->where('status', 'pending_preview')->count(),
            'high_priority' => $this->model->whereIn('priority', ['high', 'critical'])->count(),
            'today' => $this->model->whereDate('created_at', today())->count(),
            'this_week' => $this->model->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];
    }

    public function getMonthlyStats(): array
    {
        $months = [];
        $counts = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            $count = $this->model->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $counts[] = $count;
        }

        return [
            'months' => $months,
            'counts' => $counts
        ];
    }

    public function findReport(int $id): ?Report
    {
        return $this->model->with(['unit', 'student', 'rating', 'admin'])->find($id);
    }

    public function reply(int $id, string $response, int $adminId, ?string $ipAddress = null, ?string $userAgent = null, ?string $status = 'replied'): Report
    {
        $report = $this->findOrFail($id);
        $oldResponse = $report->admin_response;
        $oldStatus = $report->status;

        $report->update([
            'admin_response' => $response,
            'admin_id' => $adminId,
            'ditanggapi_pada' => now(),
            'status' => $status
        ]);

        $this->logAdminAction('reply_report', $report, null, [
            'old_response' => $oldResponse,
            'new_response' => $response,
            'old_status' => $oldStatus,
            'new_status' => $status,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent
        ]);

        return $report->fresh(['unit', 'student', 'rating', 'admin']);
    }

    public function updateStatus(int $id, array $data, int $adminId): Report
    {
        $report = $this->findOrFail($id);
        $oldStatus = $report->status;

        $updateData = [
            'status' => $data['status'],
            'admin_id' => $adminId,
        ];

        if ($data['status'] === 'replied' && isset($data['admin_response'])) {
            $updateData['admin_response'] = $data['admin_response'];
            $updateData['ditanggapi_pada'] = now();
        }

        if (in_array($data['status'], ['resolved', 'rejected']) && !$report->ditanggapi_pada) {
            $updateData['ditanggapi_pada'] = now();
        }

        $report->update($updateData);

        $this->logAdminAction('update_report_status', $report, null, [
            'old_status' => $oldStatus,
            'new_status' => $data['status']
        ]);

        return $report->fresh(['unit', 'student', 'rating', 'admin']);
    }

    public function bulkUpdateStatus(array $reportIds, string $status, ?string $adminResponse = null, int $adminId): int
    {
        $updateData = [
            'status' => $status,
            'admin_id' => $adminId,
        ];

        if ($adminResponse !== null && $status === 'replied') {
            $updateData['admin_response'] = $adminResponse;
            $updateData['ditanggapi_pada'] = now();
        }

        if (in_array($status, ['resolved', 'rejected'])) {
            $updateData['ditanggapi_pada'] = now();
        }

        $count = $this->model->whereIn('id', $reportIds)->update($updateData);

        if ($count > 0) {
            $this->logAdminAction('bulk_update_report_status', (object)[
                'report_ids' => $reportIds,
                'count' => $count
            ], null, [
                'status' => $status,
                'admin_id' => $adminId
            ]);
        }

        return $count;
    }

    public function deleteReport(int $id): bool
    {
        $report = $this->findOrFail($id);
        $result = $report->delete();

        if ($result) {
            $this->logAdminAction('delete_report', $report);
        }

        return $result;
    }

    public function export(array $filters)
    {
        $query = $this->getBaseQuery($filters)
            ->with(['unit', 'student', 'rating', 'admin']);

        $reports = $query->get();

        $filename = 'reports-export-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($reports) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Tracking Code',
                'Title',
                'Unit',
                'Student',
                'Priority',
                'Status',
                'Admin Response',
                'Replied At',
                'Created At'
            ]);

            foreach ($reports as $report) {
                fputcsv($file, [
                    $report->tracking_code,
                    $report->title,
                    $report->unit->name ?? '-',
                    $report->student->name ?? '-',
                    $report->priority,
                    $report->status,
                    $report->admin_response ?? '-',
                    $report->ditanggapi_pada ? $report->ditanggapi_pada->format('Y-m-d H:i') : '-',
                    $report->created_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function find(int $id, array $with = []): ?Report
    {
        return parent::find($id, $with);
    }

    public function findOrFail(int $id, array $with = []): Report
    {
        return parent::findOrFail($id, $with);
    }

    public function updateReport(int $id, array $data, int $adminId): Report
    {
        $report = $this->findOrFail($id);
        $oldData = [
            'title' => $report->title,
            'description' => $report->description,
            'priority' => $report->priority,
            'status' => $report->status,
            'admin_response' => $report->admin_response
        ];

        $updateData = [
            'title' => $data['title'],
            'description' => $data['description'],
            'priority' => $data['priority'],
            'status' => $data['status'],
        ];

        if (isset($data['admin_response'])) {
            $updateData['admin_response'] = $data['admin_response'];

            if ($data['status'] === 'replied' && !$report->replied_at) {
                $updateData['replied_at'] = now();
            }
        }

        $report->update($updateData);

        $this->logAdminAction('update_report', $report, null, [
            'old_data' => $oldData,
            'new_data' => $updateData,
            'admin_id' => $adminId
        ]);

        return $report->fresh(['unit', 'student', 'rating', 'admin']);
    }
}
