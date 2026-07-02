@if ($isLoading ?? false)
    @for ($i = 0; $i < ($skeletonCount ?? 10); $i++)
        <tr>
            <td class="ps-4">
                <div class="d-flex flex-column gap-1">
                    <div class="skeleton" style="width: 160px; height: 16px;"></div>
                    <div class="skeleton" style="width: 100px; height: 12px;"></div>
                </div>
            </td>
            <td>
                <div class="skeleton" style="width: 100px; height: 24px; border-radius: 12px;"></div>
            </td>
            <td>
                <div class="skeleton" style="width: 120px; height: 24px; border-radius: 12px;"></div>
            </td>
            <td>
                <div class="skeleton" style="width: 70px; height: 24px; border-radius: 12px;"></div>
            </td>
            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-2">
                    <div class="skeleton" style="width: 34px; height: 34px; border-radius: 8px;"></div>
                    <div class="skeleton" style="width: 34px; height: 34px; border-radius: 8px;"></div>
                    <div class="skeleton" style="width: 34px; height: 34px; border-radius: 8px;"></div>
                </div>
            </td>
        </tr>
    @endfor
@elseif(is_object($units) && method_exists($units, 'count') && $units->count())
    @foreach ($units as $unit)
        <tr>
            <td class="ps-4">
                <div class="fw-semibold">{{ $unit->name }}</div>
            </td>
            <td>
                @if ($unit->unitType)
                    <span class="">
                        {{ $unit->unitType->name }}
                    </span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td class="ps-4">
                @if($unit->open_time && $unit->close_time)
                    <div class="time-range-badge">
                        <span class="time-text">
                            {{ \Carbon\Carbon::parse($unit->open_time)->format('H:i') }}
                            <span class="time-separator">—</span>
                            {{ \Carbon\Carbon::parse($unit->close_time)->format('H:i') }}
                        </span>
                    </div>
                @elseif($unit->open_time)
                    <span class="time-single">{{ \Carbon\Carbon::parse($unit->open_time)->format('H:i') }} (buka)</span>
                @elseif($unit->close_time)
                    <span class="time-single">{{ \Carbon\Carbon::parse($unit->close_time)->format('H:i') }} (tutup)</span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                <x-shared.status-badge status="{{ $unit->operational_status ?? 'closed' }}" />
            </td>
            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <x-admin.button type="view" url="{{ route('admin.units.show', $unit->id) }}" tooltip="View Unit" />
                    <x-admin.button type="edit" url="{{ route('admin.units.edit', $unit->id) }}" tooltip="Edit Unit" />
                    <x-admin.button type="delete" onclick="openModal('deleteModal{{ $unit->id }}')" tooltip="Delete Unit" />
                </div>
                <x-shared.delete-modal id="deleteModal{{ $unit->id }}" title="Delete Unit" itemName="{{ $unit->name }}"
                    itemType="unit" deleteRoute="{{ route('admin.units.destroy', $unit->id) }}" deleteMethod="DELETE" />
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center py-5 text-muted">
            <div class="d-flex flex-column align-items-center justify-content-center" style="padding: 40px;">
                <div class="bg-muted p-3 rounded-circle border"
                    style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5">
                        <path d="M0 0h24v24H0z" fill="none" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 21h18M3 7v14M21 7v14M6 7V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v3M10 11h4M10 15h4" />
                    </svg>
                </div>
                <p class="text-muted mt-3 fw-medium">No units found</p>
                <a href="{{ route('admin.units.create') }}" class="btn btn-primary rounded-pill px-4 mt-2"
                    style="background: #f8773c; border: none;">
                    Add New Unit
                </a>
            </div>
        </td>
    </tr>
@endif