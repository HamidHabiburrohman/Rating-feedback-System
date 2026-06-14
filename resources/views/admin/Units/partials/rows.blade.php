@if(is_object($units) && method_exists($units, 'count') && $units->count())
    @foreach($units as $unit)
        <tr>
            <td class="ps-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="fw-semibold">{{ $unit->name }}</div>
                </div>
            </td>

            <td>
                {{ $unit->unitType->name ?? '-' }}
            </td>

            <td>
                <x-shared.status-badge :status="$unit->operational_status ?? 'open'" />
            </td>

            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <x-admin.button type="show" url="{{ route('admin.units.show', $unit->id) }}" tooltip="View Unit" />
                    <x-admin.button type="edit" url="{{ route('admin.units.edit', $unit->id) }}" tooltip="Edit Unit" />
                    <x-admin.button type="delete" onclick="openModal('deleteModal{{ $unit->id }}')" tooltip="Delete Unit" />
                </div>

                <x-shared.delete-modal 
                    :id="'deleteModal' . $unit->id" 
                    title="Delete Unit" 
                    :item-name="$unit->name"
                    itemType="unit" 
                    :delete-route="route('admin.units.destroy', $unit->id)" 
                    deleteMethod="DELETE" 
                />
            </td>
        </tr>
    @endforeach
@elseif(is_string($units) || $units === null)
    <tr>
        <td colspan="4" class="text-center py-5 text-muted">
            <div style="padding: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" style="opacity: 0.3; margin-bottom: 16px;">
                    <rect x="2" y="2" width="20" height="20" rx="2" ry="2"></rect>
                    <line x1="8" y1="2" x2="8" y2="22"></line>
                    <line x1="16" y1="2" x2="16" y2="22"></line>
                    <line x1="2" y1="8" x2="22" y2="8"></line>
                    <line x1="2" y1="16" x2="22" y2="16"></line>
                </svg>
                <p>Error loading units</p>
                @if(config('app.debug'))
                    <small class="text-danger">Debug: units is {{ gettype($units) }}</small>
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
                    <rect x="2" y="2" width="20" height="20" rx="2" ry="2"></rect>
                    <line x1="8" y1="2" x2="8" y2="22"></line>
                    <line x1="16" y1="2" x2="16" y2="22"></line>
                    <line x1="2" y1="8" x2="22" y2="8"></line>
                    <line x1="2" y1="16" x2="22" y2="16"></line>
                </svg>
                <p>No units found</p>
                <a href="{{ route('admin.units.create') }}" class="btn btn-primary rounded-pill mt-3 px-4"
                    style="background: #f8773c; border: none;">
                    Add New Unit
                </a>
            </div>
        </td>
    </tr>
@endif