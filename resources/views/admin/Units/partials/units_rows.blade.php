@if($units->count())
    @foreach($units as $unit)
        @php
            $capacity = $unit->kapasitas ?? 0;
            $capacityPercent = $capacity > 0 ? min(100, ($capacity / 100) * 100) : 0;
            $progressColor = $capacityPercent >= 90 ? 'bg-danger' : ($capacityPercent >= 70 ? 'bg-warning' : 'bg-primary');
            $statusMap = [
                true => ['label' => 'Aktif', 'class' => 'bg-success-subtle text-success'],
                false => ['label' => 'Nonaktif', 'class' => 'bg-danger-subtle text-danger']
            ];
            $status = $statusMap[$unit->status_aktif] ?? $statusMap[true];
        @endphp

        <tr>
            <td class="ps-4">
                <div class="fw-semibold">{{ $unit->nama_unit }}</div>
                <div class="text-muted" style="font-size:.75rem">
                    {{ $unit->kode_unit ?? 'No code' }}
                </div>
            </td>

            <td>
                <span class="badge rounded-pill bg-light text-dark border px-3">
                    {{ $unit->unitType->name ?? 'N/A' }}
                </span>
            </td>

            <td>
                @php
                    $statusColors = [
                        'open' => 'bg-success-subtle text-success',
                        'full' => 'bg-warning-subtle text-warning',
                        'maintenance' => 'bg-info-subtle text-info',
                        'closed' => 'bg-danger-subtle text-danger'
                    ];
                    $statusLabels = [
                        'open' => 'Open',
                        'full' => 'Full',
                        'maintenance' => 'Maintenance',
                        'closed' => 'Closed'
                    ];
                    $statusClass = $statusColors[$unit->status] ?? 'bg-secondary-subtle text-secondary';
                    $statusLabel = $statusLabels[$unit->status] ?? $unit->status;
                @endphp
                <span class="badge rounded-pill px-3 {{ $statusClass }}">
                    {{ $statusLabel }}
                </span>
            </td>

            <td style="min-width:160px">
                <div class="progress" style="height:6px">
                    <div class="progress-bar {{ $progressColor }}" style="width:{{ $capacityPercent }}%"></div>
                </div>
                <small class="text-muted">
                    @if($capacity > 0)
                        {{ $capacity }} orang
                    @else
                        Tidak ditentukan
                    @endif
                </small>
            </td>

            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <a href="{{ route('admin.units.show', $unit->id) }}"
                        class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn"
                        data-bs-toggle="tooltip" data-bs-title="View Details">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </a>

                    <a href="{{ route('admin.units.edit', $unit->id) }}"
                        class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn"
                        data-bs-toggle="tooltip" data-bs-title="Edit Unit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </a>

                    <button type="button"
                        class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn btn-trigger-delete"
                        data-bs-toggle="tooltip" data-bs-title="Delete Unit" onclick="openModal('deleteModal{{ $unit->id }}')">
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

                <x-delete-modal id="deleteModal{{ $unit->id }}" title="Delete Unit" itemName="{{ $unit->nama_unit }}"
                    itemType="unit" deleteRoute="{{ route('admin.units.destroy', $unit->id) }}" deleteMethod="DELETE" />

            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="6" class="text-center py-5 text-muted">
            No units found
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

    .btn-trigger-delete:hover {
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

    .btn-trigger-delete:hover .pulse-ring {
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

    .custom-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        visibility: hidden;
        opacity: 0;
        transition: visibility 0.3s, opacity 0.3s ease;
        pointer-events: none;
    }

    .custom-modal.show {
        visibility: visible;
        opacity: 1;
        pointer-events: auto;
    }

    .custom-modal-backdrop {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
    }

    .custom-modal-dialog {
        position: relative;
        width: 90%;
        max-width: 420px;
        z-index: 10000;
        margin: auto;
        transform: scale(0.9) translateY(10px);
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .custom-modal.show .custom-modal-dialog {
        transform: scale(1) translateY(0);
    }

    .custom-modal-content {
        background: white;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .custom-modal-header {
        padding: 32px 28px 16px;
        position: relative;
        text-align: center;
    }

    .warning-icon-container {
        width: 72px;
        height: 72px;
        margin: 0 auto 20px;
        background: #fee2e2;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #dc2626;
        animation: iconPulse 2s infinite ease-in-out;
    }

    .warning-icon {
        width: 36px;
        height: 36px;
        stroke: currentColor;
        stroke-width: 2;
        fill: none;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .modal-title {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px;
        letter-spacing: -0.01em;
    }

    .modal-subtitle {
        font-size: 15px;
        color: #6b7280;
        margin: 0;
        line-height: 1.5;
    }

    .modal-close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: none;
        background: #f3f4f6;
        color: #6b7280;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .modal-close-btn:hover {
        background: #e5e7eb;
        color: #374151;
        transform: rotate(90deg);
    }

    .custom-modal-body {
        padding: 0 32px;
    }

    .warning-text {
        font-size: 14px;
        color: #6b7280;
        text-align: center;
        line-height: 1.6;
        margin: 0;
        padding: 0 0 28px;
    }

    .custom-modal-footer {
        padding: 20px 32px 32px;
        background: #f9fafb;
        border-top: 1px solid #f3f4f6;
    }

    .footer-buttons {
        display: flex;
        gap: 12px;
    }

    .btn-cancel,
    .btn-delete {
        flex: 1;
        padding: 12px 20px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-cancel {
        background: white;
        color: #374151;
        border: 1px solid #d1d5db;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .btn-cancel:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }

    .btn-delete {
        background: #dc2626;
        color: white;
        box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.3);
    }

    .btn-delete:hover {
        background: #b91c1c;
        transform: translateY(-1px);
        box-shadow: 0 6px 8px -1px rgba(220, 38, 38, 0.4);
    }

    .loading-spinner {
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes iconPulse {

        0%,
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.1);
        }

        50% {
            transform: scale(1.05);
        }

        70% {
            box-shadow: 0 0 0 12px rgba(239, 68, 68, 0);
        }
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 480px) {
        .custom-modal-dialog {
            width: 95%;
        }

        .footer-buttons {
            flex-direction: column;
        }

        .btn-cancel,
        .btn-delete {
            width: 100%;
        }
    }
</style>

<script>
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            document.body.style.overflow = 'hidden';
            modal.classList.add('show');
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-form');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const submitButton = this.querySelector('.btn-delete');
                const buttonText = submitButton.querySelector('.btn-text');
                const spinner = submitButton.querySelector('.loading-spinner');

                if (submitButton && !submitButton.disabled) {
                    buttonText.style.display = 'none';
                    spinner.style.display = 'block';
                    submitButton.disabled = true;
                    submitButton.style.opacity = '0.7';
                    this.submit();
                }
            });
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const openModalEl = document.querySelector('.custom-modal.show');
                if (openModalEl) {
                    closeModal(openModalEl.id);
                }
            }
        });
    });
</script>