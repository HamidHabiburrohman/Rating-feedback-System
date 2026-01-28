@if($unitTypes->count())
    @foreach($unitTypes as $type)
        @php
            $statusMap = [
                true => ['label' => 'Active', 'class' => 'bg-success-subtle text-success'],
                false => ['label' => 'Inactive', 'class' => 'bg-danger-subtle text-danger']
            ];
            $status = $statusMap[$type->is_active] ?? $statusMap[true];
        @endphp

        <tr class="border-bottom" data-id="{{ $type->id }}">
            <td class="ps-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px; border: 1px solid #e5e7eb; background-color: #f9fafb;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="#6b7280" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                            <line x1="9" y1="3" x2="9" y2="21" />
                        </svg>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-0" style="color: #1a1a1a;">{{ $type->name }}</h6>
                    </div>
                </div>
            </td>

            <td>
                <span class="text-muted">{{ $type->description ?? 'No description' }}</span>
            </td>

            <td>
                <span class="badge rounded-pill px-3 {{ $status['class'] }}">
                    {{ $status['label'] }}
                </span>
            </td>

            <td>
                <span class="badge rounded-pill px-3 py-1 fw-medium"
                    style="background: white; color: #374151; border: 1px solid #e5e7eb;">
                    {{ $type->units_count ?? 0 }} units
                </span>
            </td>

            <td class="pe-4">
                <div class="d-flex align-items-center justify-content-center gap-1">
                    <a href="{{ route('admin.unit-types.edit', $type->id) }}"
                        class="btn btn-sm btn-outline-primary rounded-circle d-flex align-items-center justify-content-center action-btn"
                        style="width: 32px; height: 32px;" title="Edit" data-bs-toggle="tooltip" data-bs-title="Edit Type">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                    </a>

                    <button type="button"
                        class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center action-btn btn-trigger-delete"
                        style="width: 32px; height: 32px;" data-id="{{ $type->id }}" data-name="{{ $type->name }}"
                        title="Delete" data-bs-toggle="tooltip" data-bs-title="Delete Type"
                        onclick="openModal('deleteModal{{ $type->id }}')">
                        <div class="pulse-ring"></div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6" />
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                            <line x1="10" y1="11" x2="10" y2="17" />
                            <line x1="14" y1="11" x2="14" y2="17" />
                        </svg>
                    </button>
                </div>

                {{-- Delete Modal --}}
                <div class="custom-modal" id="deleteModal{{ $type->id }}">
                    <div class="custom-modal-backdrop" onclick="closeModal('deleteModal{{ $type->id }}')"></div>
                    <div class="custom-modal-dialog">
                        <div class="custom-modal-content">
                            <div class="custom-modal-header">
                                <div class="header-content">
                                    <div class="warning-icon-container">
                                        <svg class="warning-icon" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                    </div>
                                    <h2 class="modal-title">Delete Unit Type</h2>
                                    <p class="modal-subtitle">You're going to delete unit type
                                        <strong>"{{ $type->name }}"</strong>
                                    </p>
                                </div>
                                <button class="modal-close-btn" onclick="closeModal('deleteModal{{ $type->id }}')">
                                    <svg viewBox="0 0 24 24" width="20" height="20">
                                        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" />
                                    </svg>
                                </button>
                            </div>

                            <div class="custom-modal-body">
                                <p class="warning-text">This action cannot be undone.
                                    @if($type->units_count > 0)
                                        <strong class="text-danger">This type has {{ $type->units_count }} unit(s). Deleting it will
                                            affect those units.</strong>
                                    @endif
                                </p>
                            </div>

                            <div class="custom-modal-footer">
                                <div class="footer-buttons">
                                    <button class="btn-cancel" onclick="closeModal('deleteModal{{ $type->id }}')">
                                        No, keep it.
                                    </button>
                                    <form method="POST" action="{{ route('admin.unit-types.destroy', $type->id) }}"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">
                                            <span class="btn-text">Yes, Delete!</span>
                                            <span class="loading-spinner" style="display: none;"></span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="6" class="text-center py-5 text-muted">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1" class="mb-3 opacity-50">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                <line x1="9" y1="3" x2="9" y2="21" />
            </svg>
            <h5 class="fw-medium mb-2">No unit types found</h5>
            <p class="mb-0">Start by adding your first unit type</p>
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

        // Toggle status functionality
        document.querySelectorAll('.status-toggle').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('.toggle-status-form');
                const typeId = form.dataset.id;
                const isActive = this.dataset.active === 'true';

                fetch(`/admin/unit-types/${typeId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.dataset.active = !isActive;
                            if (!isActive) {
                                this.classList.remove('btn-outline-secondary');
                                this.classList.add('btn-success');
                                this.textContent = 'Active';
                            } else {
                                this.classList.remove('btn-success');
                                this.classList.add('btn-outline-secondary');
                                this.textContent = 'Inactive';
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
</script>