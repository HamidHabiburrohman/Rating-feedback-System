@extends('layouts.admin.app')

@section('admin-content')
<div class="container-fluid px-4 py-4">
    <div class="mb-4">
        <h2 class="fw-bold">Edit Report: {{ $report->tracking_code }}</h2>
    </div>

    <form action="{{ route('admin.reports.update', $report->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-md-8">
                <div class="card border-1 rounded-4 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Title</label>
                            <input type="text" name="judul" class="form-control" value="{{ $report->judul }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Description</label>
                            <textarea name="deskripsi" class="form-control" rows="5" required>{{ $report->deskripsi }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card border-1 rounded-4 shadow-sm border-primary">
                    <div class="card-body p-4">
                        <label class="form-label fw-bold small text-primary">Admin Response</label>
                        <textarea name="tanggapan_admin" class="form-control border-primary" rows="4" placeholder="Write response to visitor...">{{ $report->tanggapan_admin }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-1 rounded-4 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Status</label>
                            <select name="status" class="form-select shadow-sm">
                                <option value="baru" {{ $report->status == 'baru' ? 'selected' : '' }}>New</option>
                                <option value="diproses" {{ $report->status == 'diproses' ? 'selected' : '' }}>Processing</option>
                                <option value="selesai" {{ $report->status == 'selesai' ? 'selected' : '' }}>Completed</option>
                                <option value="ditolak" {{ $report->status == 'ditolak' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Priority Level</label>
                            <select name="prioritas" class="form-select shadow-sm">
                                <option value="rendah" {{ $report->prioritas == 'rendah' ? 'selected' : '' }}>Low</option>
                                <option value="sedang" {{ $report->prioritas == 'sedang' ? 'selected' : '' }}>Medium</option>
                                <option value="tinggi" {{ $report->prioritas == 'tinggi' ? 'selected' : '' }}>High</option>
                                <option value="kritis" {{ $report->prioritas == 'kritis' ? 'selected' : '' }}>Critical</option>
                            </select>
                        </div>
                        <hr>
                        <div class="small text-muted">
                            <div>Visitor IP: {{ $report->visitor_session->ip_address ?? 'N/A' }}</div>
                            <div>Sesi ID: {{ Str::limit($report->visitor_session->session_id ?? 'N/A', 15) }}</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill mt-4" style="background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none;">Update Report</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection