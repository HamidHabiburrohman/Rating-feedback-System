@if(is_object($departments) && method_exists($departments, 'count') && $departments->count())
    @foreach($departments as $department)
        @php
            $statusMap = [
                true => ['label' => 'Active', 'class' => 'bg-success-subtle text-success'],
                false => ['label' => 'Inactive', 'class' => 'bg-danger-subtle text-danger']
            ];
            $status = $statusMap[$department->is_active] ?? $statusMap[true];
        @endphp

        <tr>
            <td class="ps-4">
                <div class="fw-semibold">{{ $department->name }}</div>
            </td>

            <td>
                @if($department->code)
                    <span class="badge rounded-pill bg-light text-dark border px-3">
                        {{ $department->code }}
                    </span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>

            <td>
                <span class="badge rounded-pill bg-light text-dark border px-3">
                    {{ $department->units_count ?? 0 }} units
                </span>
            </td>

            <td>
                <x-admin.status-badge status="{{ $department->is_active ? 'active' : 'inactive' }}" />
            </td>

            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <x-admin.button type="edit" url="{{ route('admin.unit-departments.edit', $department->id) }}" tooltip="Edit Department"/>

                        <x-admin.button type="delete" onclick="openModal('deleteModal{{ $department->id }}')" tooltip="Delete Department" />
                    </div>

                <x-admin.delete-modal-component id="deleteModal{{ $department->id }}" title="Delete Department"
                    itemName="{{ $department->name }}" itemType="department"
                    deleteRoute="{{ route('admin.unit-departments.destroy', $department->id) }}" deleteMethod="DELETE" />
        </tr>
    @endforeach
@elseif(is_string($departments) || $departments === null)
    <tr>
        <td colspan="5" class="text-center py-5 text-muted">
            <div style="padding: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" style="opacity: 0.3; margin-bottom: 16px;">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                </svg>
                <p>Error loading departments</p>
                @if(config('app.debug'))
                    <small class="text-danger">Debug: departments is {{ gettype($departments) }}</small>
                @endif
            </div>
        </td>
    </tr>
@else
    <tr>
        <td colspan="5" class="text-center py-5 text-muted">
            <div style="padding: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" style="opacity: 0.3; margin-bottom: 16px;">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                </svg>
                <p>No departments found</p>
            </div>
        </td>
    </tr>
@endif