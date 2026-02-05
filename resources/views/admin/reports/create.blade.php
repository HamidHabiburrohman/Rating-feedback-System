@extends('layouts.admin.app')

@section('admin-content')
<div class="container-fluid px-4 py-4">
    <div class="mb-4">
        <a href="{{ route('admin.reports.index') }}" class="text-decoration-none text-muted small d-flex align-items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg> Back to Reports
        </a>
        <h2 class="fw-bold mt-2">Create New Report</h2>
    </div>

    <div class="card border-1 rounded-4 shadow-sm">
        <form action="{{ route('admin.reports.store') }}" method="POST">
            @csrf
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Report Title</label>
                            <input type="text" name="judul" class="form-control rounded-3" placeholder="e.g. Broken AC in Room 302" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Detailed Description</label>
                            <textarea name="deskripsi" class="form-control rounded-3" rows="6" placeholder="Provide full details..." required></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-4 border">
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Unit</label>
                                <select name="unit_id" class="form-select rounded-3 shadow-sm" required>
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Type</label>
                                <select name="tipe" class="form-select rounded-3 shadow-sm" required>
                                    <option value="masalah">Problem</option>
                                    <option value="saran">Suggestion</option>
                                    <option value="keluhan">Complaint</option>
                                    <option value="lainnya">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Priority</label>
                                <select name="prioritas" class="form-select rounded-3 shadow-sm">
                                    <option value="rendah">Low</option>
                                    <option value="sedang" selected>Medium</option>
                                    <option value="tinggi">High</option>
                                    <option value="kritis">Critical</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent p-4 border-top-0 d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-4" style="background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none;">Create Report</button>
            </div>
        </form>
    </div>
</div>
@endsection