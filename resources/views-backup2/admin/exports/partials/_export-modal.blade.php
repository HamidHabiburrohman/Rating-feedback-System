<div class="modal fade" id="exportFilterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <div class="modal-header" style="background: linear-gradient(135deg, #f1c3ae, #f8773c); padding: 1.5rem 2rem; border: none;">
                <div>
                    <h5 class="modal-title fw-bold text-white" style="font-size: 1.25rem;">Export Options</h5>
                    <p class="text-white-50 small mb-0 mt-1">Customize your export</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding: 2rem; background: #f8fafc;">
                <form id="exportFilterForm">
                    @csrf
                    <input type="hidden" name="type" id="exportType">
                    <input type="hidden" name="format" id="exportFormat">

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Date Range</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="date" class="form-control" name="date_from" id="dateFrom" placeholder="From">
                            </div>
                            <div class="col-6">
                                <input type="date" class="form-control" name="date_to" id="dateTo" placeholder="To">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4" id="filterStatusContainer" style="display: none;">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" name="status" id="filterStatus">
                            <option value="">All</option>
                            <option value="new">New</option>
                            <option value="in_progress">In Progress</option>
                            <option value="replied">Replied</option>
                            <option value="resolved">Resolved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <div class="mb-4" id="filterPriorityContainer" style="display: none;">
                        <label class="form-label fw-semibold">Priority</label>
                        <select class="form-select" name="priority" id="filterPriority">
                            <option value="">All</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>

                    <div class="mb-4" id="filterUnitContainer" style="display: none;">
                        <label class="form-label fw-semibold">Unit</label>
                        <select class="form-select" name="unit_id" id="filterUnit">
                            <option value="">All Units</option>
                            @foreach(\App\Models\Unit::all() as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4" id="filterScoreContainer" style="display: none;">
                        <label class="form-label fw-semibold">Score Range</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" class="form-control" name="min_score" id="minScore" placeholder="Min" min="1" max="5" step="0.1">
                            </div>
                            <div class="col-6">
                                <input type="number" class="form-control" name="max_score" id="maxScore" placeholder="Max" min="1" max="5" step="0.1">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer" style="padding: 1.25rem 2rem; background: white; border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="applyExportFilter" style="background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Export
                </button>
            </div>
        </div>
    </div>
</div>