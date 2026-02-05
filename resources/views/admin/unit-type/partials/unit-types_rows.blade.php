@if($unitTypes->count())
    @foreach($unitTypes as $type)
        @php
            $statusMap = [
                true => ['label' => 'Active', 'class' => 'bg-success-subtle text-success'],
                false => ['label' => 'Inactive', 'class' => 'bg-danger-subtle text-danger']
            ];
            $status = $statusMap[$type->is_active] ?? $statusMap[true];
        @endphp

        <tr>
            <td class="ps-4">
                <div class="fw-semibold">{{ $type->name }}</div>
                <div class="text-muted" style="font-size:.75rem">
                    {{ $type->description ?? 'No description' }}
                </div>
            </td>

            <td>
                <span class="badge rounded-pill bg-light text-dark border px-3">
                    {{ $type->units_count ?? 0 }} units
                </span>
            </td>

            <td>
                <!-- Status badge untuk display -->
                <span class="badge rounded-pill px-3 ms-2 {{ $status['class'] }}" id="status_badge_{{ $type->id }}">
                    {{ $status['label'] }}
                </span>
            </td>

            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <a href="{{ route('admin.unit-types.edit', $type->id) }}"
                        class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn"
                        data-bs-toggle="tooltip" data-bs-title="Edit Type">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </a>

                    <button type="button"
                        class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn btn-trigger-delete"
                        data-bs-toggle="tooltip" data-bs-title="Delete Type" onclick="openModal('deleteModal{{ $type->id }}')">
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

                <x-delete-modal id="deleteModal{{ $type->id }}" title="Delete Unit Type" itemName="{{ $type->name }}"
                    itemType="unit type" deleteRoute="{{ route('admin.unit-types.destroy', $type->id) }}"
                    deleteMethod="DELETE" />
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="4" class="text-center py-5 text-muted">
            No unit types found
        </td>
    </tr>
@endif



<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle toggle switch
        document.querySelectorAll('.status-toggle').forEach(toggle => {
            toggle.addEventListener('change', function () {
                const unitTypeId = this.getAttribute('data-id');
                const isChecked = this.checked;

                // Show loading state
                const badge = document.getElementById(`status_badge_${unitTypeId}`);
                const originalBadgeHtml = badge.innerHTML;
                badge.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
                badge.className = 'badge rounded-pill px-3 ms-2 bg-secondary';

                this.disabled = true;

                // Send AJAX request
                fetch(`/admin/unit-types/${unitTypeId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Update badge based on response
                            const newStatus = data.data.is_active;
                            const statusText = newStatus ? 'Active' : 'Inactive';
                            const statusClass = newStatus ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';

                            badge.innerHTML = statusText;
                            badge.className = `badge rounded-pill px-3 ms-2 ${statusClass}`;

                            // Show success toast
                            showToast('success', data.message);

                            // Update checkbox state based on response (untuk konsistensi)
                            this.checked = newStatus;
                        } else {
                            // Revert toggle if failed
                            this.checked = !isChecked;
                            badge.innerHTML = originalBadgeHtml;
                            showToast('error', data.message);
                        }
                    })
                    .catch(error => {
                        // Revert toggle on error
                        this.checked = !isChecked;
                        badge.innerHTML = originalBadgeHtml;
                        badge.className = `badge rounded-pill px-3 ms-2 ${isChecked ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'}`;
                        showToast('error', 'Network error: ' + error.message);
                        console.error('Toggle error:', error);
                    })
                    .finally(() => {
                        this.disabled = false;
                    });
            });
        });

        // Toast notification function
        function showToast(type, message) {
            // Create toast container if not exists
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '9999';
                document.body.appendChild(toastContainer);
            }

            // Create toast
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');

            toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="ti ti-${type === 'success' ? 'circle-check' : 'alert-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

            toastContainer.appendChild(toast);

            // Initialize and show Bootstrap toast
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();

            // Remove toast after it's hidden
            toast.addEventListener('hidden.bs.toast', function () {
                toast.remove();
            });
        }
    });
</script>