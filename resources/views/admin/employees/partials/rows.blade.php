@if (is_object($employees) && method_exists($employees, 'count') && $employees->count())
    @foreach ($employees as $employee)
        @php
            $assignedUnits = $employee
                ->unitAssignments()
                ->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('ended_at')->orWhere('ended_at', '>', now());
                })
                ->with('unit')
                ->get();
        @endphp

        <tr>
            <td class="ps-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="fw-semibold">{{ $employee->name }}</div>
                </div>
            </td>

            <td>{{ $employee->email ?? '-' }}</td>

            <td>
                @if ($assignedUnits->count() > 0)
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark border" style="font-size: 0.75rem; font-weight: 500;">
                            {{ $assignedUnits->first()->unit->name ?? 'Unknown' }}
                            @if ($assignedUnits->first()->role_in_unit)
                                ({{ $assignedUnits->first()->role_in_unit }})
                            @endif
                        </span>
                        @if ($assignedUnits->count() > 1)
                            <span class="badge text-white rounded-pill d-flex align-items-center justify-content-center"
                                style="font-size: 0.7rem; font-weight: 600; background: #f8773c; min-width: 24px; height: 24px; padding: 0 6px;">
                                +{{ $assignedUnits->count() - 1 }}
                            </span>
                        @endif
                    </div>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>

            <td>
                <x-shared.status-badge :status="$employee->is_active ? 'active' : 'inactive'" />
            </td>

            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <x-admin.button type="show" url="{{ route('admin.employees.show', $employee->id) }}"
                        tooltip="View Employee" />
                    <x-admin.button type="edit" url="{{ route('admin.employees.edit', $employee->id) }}"
                        tooltip="Edit Employee" />
                    <x-admin.button type="delete" onclick="openModal('deleteModal{{ $employee->id }}')"
                        tooltip="Delete Employee" />
                </div>

                <x-shared.delete-modal :id="'deleteModal' . $employee->id" title="Delete Employee" :item-name="$employee->name" itemType="employee"
                    :delete-route="route('admin.employees.destroy', $employee->id)" deleteMethod="DELETE" />
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center py-5 text-muted">
            <div style="padding: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" style="opacity: 0.3; margin-bottom: 16px;">
                    <rect x="2" y="2" width="20" height="20" rx="2" ry="2"></rect>
                    <line x1="8" y1="2" x2="8" y2="22"></line>
                    <line x1="16" y1="2" x2="16" y2="22"></line>
                    <line x1="2" y1="8" x2="22" y2="8"></line>
                    <line x1="2" y1="16" x2="22" y2="16"></line>
                </svg>
                <p>No employees found</p>
                <a href="{{ route('admin.employees.create') }}" class="btn btn-primary rounded-pill mt-3 px-4"
                    style="background: #f8773c; border: none;">
                    Add New Employee
                </a>
            </div>
        </td>
    </tr>
@endif
