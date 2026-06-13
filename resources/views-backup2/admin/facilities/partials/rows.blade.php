@if(is_object($facilities) && method_exists($facilities, 'count') && $facilities->count())
    @foreach($facilities as $facility)
        <tr>
            <td class="ps-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center bg-transparent border border-gray-200 rounded-circle shrink-0"
                        style="width: 40px; height: 40px;">
                        @if($facility->icon_key && method_exists($facility, 'getIconSvg'))
                            <div style="color: #f8773c; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;">
                                {!! $facility->getIconSvg() !!}
                            </div>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                style="color: #9ca3af;">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                        @endif
                    </div>
                    <div class="fw-semibold">{{ $facility->name }}</div>
                </div>
            </td>
            <td>
                <span class="badge rounded-pill bg-light text-dark border px-3">
                    {{ $facility->units_count ?? 0 }} units
                </span>
            </td>
            <td>
                <x-admin.status-badge status="{{ $facility->is_active ? 'active' : 'inactive' }}" />
            </td>
            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <x-admin.button type="edit" url="{{ route('admin.facilities.edit', $facility->id) }}" tooltip="Edit Facility" />
                    <x-admin.button type="delete" onclick="openModal('deleteModal{{ $facility->id }}')" tooltip="Delete Facility" />
                </div>
                <x-admin.delete-modal-component id="deleteModal{{ $facility->id }}" title="Delete Facility" itemName="{{ $facility->name }}"
                    itemType="facility" deleteRoute="{{ route('admin.facilities.destroy', $facility->id) }}"
                    deleteMethod="DELETE" />
            </td>
        </tr>
    @endforeach
@elseif(is_string($facilities) || $facilities === null)
    <tr>
        <td colspan="4" class="text-center py-5 text-muted">
            <div style="padding: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" style="opacity: 0.3; margin-bottom: 16px;">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                </svg>
                <p>Error loading facilities</p>
                @if(config('app.debug'))
                    <small class="text-danger">Debug: facilities is {{ gettype($facilities) }}</small>
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
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                </svg>
                <p>No facilities found</p>
            </div>
        </td>
    </tr>
@endif