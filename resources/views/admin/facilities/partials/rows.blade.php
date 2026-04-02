@if(is_object($facilities) && method_exists($facilities, 'count') && $facilities->count())
    @foreach($facilities as $facility)
        <tr>
            <td class="ps-4">
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center justify-content-center bg-transparent border border-gray-200 rounded-circle shrink-0"
            style="width: 40px; height: 40px;">
            @if($facility->icon_key)
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            style="color: #f8773c;">
                            @switch($facility->icon_key)
                                @case('air-conditioner')
                                    <path d="M20 16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2"/>
                                    <path d="M6 8h12"/>
                                    <path d="M12 2v2"/>
                                    <path d="M12 22v-2"/>
                                    <path d="M22 12h-2"/>
                                    <path d="M4 12H2"/>
                                    @break
                                @case('wifi')
                                    <path d="M5 13a10 10 0 0 1 14 0"/>
                                    <path d="M8.5 16.5a5 5 0 0 1 7 0"/>
                                    <circle cx="12" cy="18" r="1"/>
                                    @break
                                @case('projector')
                                    <rect x="2" y="6" width="20" height="12" rx="2"/>
                                    <path d="M8 6V4"/>
                                    <path d="M16 6V4"/>
                                    @break
                                @case('whiteboard')
                                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                                    <line x1="2" y1="8" x2="22" y2="8"/>
                                    @break
                                @case('computer')
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                    <line x1="8" y1="21" x2="16" y2="21"/>
                                    <line x1="12" y1="17" x2="12" y2="21"/>
                                    @break
                                @case('printer')
                                    <polyline points="6 9 6 2 18 2 18 9"/>
                                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                    <rect x="6" y="14" width="12" height="8"/>
                                    @break
                                @case('mosque')
                                    <path d="M12 2L2 7v10l10 5 10-5V7l-10-5z"/>
                                    <path d="M2 7l10 5 10-5"/>
                                    <path d="M12 22V12"/>
                                    @break
                                @case('toilet')
                                    <path d="M4 8h16"/>
                                    <path d="M4 16h16"/>
                                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                                    @break
                                @case('cafe')
                                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"/>
                                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/>
                                    <line x1="6" y1="1" x2="6" y2="4"/>
                                    <line x1="10" y1="1" x2="10" y2="4"/>
                                    <line x1="14" y1="1" x2="14" y2="4"/>
                                    @break
                                @case('parking')
                                    <path d="M7 17v3"/>
                                    <path d="M17 17v3"/>
                                    <path d="M5 10h14"/>
                                    <rect x="3" y="4" width="18" height="13" rx="2"/>
                                    @break
                                @case('wheelchair')
                                    <circle cx="16" cy="18" r="2"/>
                                    <circle cx="6" cy="16" r="2"/>
                                    <path d="M12 8v6"/>
                                    <path d="M16 8v2"/>
                                    <path d="M8 12h6"/>
                                    @break
                                @default
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                            @endswitch
                        </svg>
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