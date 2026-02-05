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
                        data-bs-toggle="tooltip" data-bs-title="Delete Unit" onclick="openModal('deleteModal{{ $unit->id }}')"
                        aria-label="Delete Unit {{ $unit->nama_unit }}">
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

                <x-delete-modal id="deleteModal{{ $unit->id }}" title="Delete Unit" :itemName="$unit->nama_unit" itemType="unit"
                    :deleteRoute="route('admin.units.destroy', $unit->id)" deleteMethod="DELETE" />
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