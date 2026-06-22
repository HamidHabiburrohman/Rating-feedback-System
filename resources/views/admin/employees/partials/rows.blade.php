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
                    <div class="d-flex flex-wrap gap-1">
                        @foreach ($assignedUnits->take(1) as $assignment)
                            <span class="text-dark" style="font-size: 0.75rem; font-weight: 500;">
                                {{ $assignment->unit->name ?? 'Unknown' }}
                                @if ($assignment->role_in_unit)
                                    ({{ $assignment->role_in_unit }})
                                @endif
                            </span>
                        @endforeach
                        @if ($assignedUnits->count() > 1)
                            <span class="badge text-dark rounded-pill d-flex align-items-center justify-content-center border"
                                style="font-size: 0.7rem; font-weight: 600; background: transparent; min-width: 24px; height: 24px; padding: 0 6px;">
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
            <div class="d-flex flex-column align-items-center justify-content-center" style="padding: 40px;">
                <div class="bg-muted p-3 rounded-circle border"
                    style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-width="1.5">
                            <path
                                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM5.25 9.75a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </g>
                    </svg>
                </div>

                <p class="text-muted mt-1">No employees found</p>
                <a href="{{ route('admin.employees.create') }}" class="btn btn-primary rounded-pill px-4"
                    style="background: #f8773c; border: none;">
                    Add New Employee
                </a>
            </div>
        </td>
    </tr>
@endif
