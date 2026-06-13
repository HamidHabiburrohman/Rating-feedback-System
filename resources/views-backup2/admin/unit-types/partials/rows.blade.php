@if(is_object($unitTypes) && method_exists($unitTypes, 'count') && $unitTypes->count())
    @foreach($unitTypes as $type)
        @php
            $statusMap = [
                true => ['label' => 'Active', 'class' => 'bg-success-subtle text-success'],
                false => ['label' => 'Inactive', 'class' => 'bg-danger-subtle text-danger']
            ];
            $status = $statusMap[$type->is_active] ?? $statusMap[true];
            $hasUnits = ($type->units_count ?? 0) > 0;
        @endphp

        <tr>
            <td class="ps-4">
                <div class="fw-semibold">{{ $type->name }}</div>
            </td>

            <td>
                <span class="badge rounded-pill bg-light text-dark border px-3">
                    {{ $type->units_count ?? 0 }} units
                </span>
            </td>

            <td>
                <x-admin.status-badge status="{{ $type->is_active ? 'active' : 'inactive' }}" />
            </td>

            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <x-admin.button type="edit" url="{{ route('admin.unit-types.edit', $type->id) }}" tooltip="Edit Type"/>

                    @if($hasUnits)
                        <x-admin.button type="delete" disabled="true"
                            disabledTooltip="Tidak dapat dihapus - masih memiliki {{ $type->units_count }} unit"
                            tooltip="Delete Type" />
                    @else
                        <x-admin.button type="delete" onclick="openModal('deleteModal{{ $type->id }}')" tooltip="Delete Type" />
                    @endif
                </div>

                @if(!$hasUnits)
                    <x-admin.delete-modal-component id="deleteModal{{ $type->id }}" title="Delete Unit Type"
                        itemName="{{ $type->name }}" itemType="unit type"
                        deleteRoute="{{ route('admin.unit-types.destroy', $type->id) }}" deleteMethod="DELETE" />
                @endif
            </td>
        </tr>
    @endforeach
@elseif(is_string($unitTypes) || $unitTypes === null)
    <tr>
        <td colspan="4" class="text-center py-5 text-muted">
            <div style="padding: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" style="opacity: 0.3; margin-bottom: 16px;">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                </svg>
                <p>Error loading unit types</p>
                @if(config('app.debug'))
                    <small class="text-danger">Debug: unitTypes is {{ gettype($unitTypes) }}</small>
                @endif
            </div>
        </td>
    </tr>
@else
    <tr>
        <td colspan="4" class="text-center py-5 text-muted">
            <div style="padding: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" style="opacity: 0.3; margin-bottom: 16px;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <p class="mb-0">No unit types found</p>
                <small class="text-muted">Click "Create Unit Type" to add one</small>
            </div>
        </td>
    </tr>
@endif