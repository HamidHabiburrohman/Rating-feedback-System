@if($employees->count())
    @foreach($employees as $employee)
        <tr>
            <td class="ps-4">
                <div class="fw-semibold">{{ $employee->nama }}</div>
                <div class="text-muted" style="font-size:.75rem">
                    {{ $employee->jabatan }}
                </div>
            </td>

            <td>
                @if($employee->unit)
                    <span class="badge rounded-pill bg-light text-dark border px-3">
                        {{ $employee->unit->nama_unit ?? 'N/A' }}
                    </span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>

            <td>
                @if($employee->bidang)
                    <span class="badge rounded-pill bg-light text-dark border px-3">
                        {{ $employee->bidang }}
                    </span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>

            <td class="text-center">
                @php
                    $statusClass = match ($employee->status) {
                        'aktif' => 'badge-status-aktif',
                        'cuti' => 'badge-status-cuti',
                        'resign' => 'badge-status-resign',
                        default => 'badge-secondary'
                    };
                    $statusLabel = match ($employee->status) {
                        'aktif' => 'Aktif',
                        'cuti' => 'Cuti',
                        'resign' => 'Resign',
                        default => $employee->status
                    };
                @endphp
                <span class="badge rounded-pill badge-status {{ $statusClass }}">
                    {{ $statusLabel }}
                </span>
            </td>

            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <a href="{{ route('admin.employees.show', $employee->id) }}"
                        class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn"
                        data-bs-toggle="tooltip" data-bs-title="View Details">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </a>

                    <a href="{{ route('admin.employees.edit', $employee->id) }}"
                        class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn"
                        data-bs-toggle="tooltip" data-bs-title="Edit Employee">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </a>

                    <button type="button"
                        class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn delete-btn"
                        data-bs-toggle="tooltip" data-bs-title="Delete Employee"
                        onclick="openModal('deleteModal{{ $employee->id }}')">
                        <div class="pulse-ring"></div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
        <x-delete-modal id="deleteModal{{ $employee->id }}" title="Delete Employee" itemName="{{ $employee->nama }}"
            itemType="employee" deleteRoute="{{ route('admin.employees.destroy', $employee->id) }}" deleteMethod="DELETE" />

    @endforeach
@else
    <tr>
        <td colspan="6" class="text-center py-5 text-muted">
            No employees found
        </td>
    </tr>
@endif

<style>
    .action-btn {
        width: 32px;
        height: 32px;
        border-color: #d1d5db;
        color: #6b7280;
        transition: all 0.2s ease;
        position: relative;
        overflow: visible;
        background: white;
    }

    .action-btn:hover {
        background-color: #f3f4f6;
        color: #111827;
        border-color: #9ca3af;
    }

    .delete-btn:hover {
        background-color: #fef2f2;
        border-color: #dc2626;
        color: #dc2626;
    }

    .pulse-ring {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        height: 100%;
        border-radius: 50%;
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7);
        opacity: 0;
        pointer-events: none;
    }

    .delete-btn:hover .pulse-ring {
        animation: rippleEffect 1.5s infinite cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes rippleEffect {
        0% {
            width: 0%;
            height: 0%;
            opacity: 0.5;
        }

        100% {
            width: 250%;
            height: 250%;
            opacity: 0;
        }
    }
</style>