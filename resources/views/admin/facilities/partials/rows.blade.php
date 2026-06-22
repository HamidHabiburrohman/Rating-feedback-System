@if ($isLoading ?? false)
    @for ($i = 0; $i < ($skeletonCount ?? 10); $i++)
        <tr>
            <td class="ps-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="skeleton skeleton-circle" style="width: 36px; height: 36px;"></div>
                    <div class="skeleton" style="width: 160px; height: 16px;"></div>
                </div>
            </td>
            <td>
                <div class="skeleton" style="width: 70px; height: 24px; border-radius: 12px;"></div>
            </td>
            <td>
                <div class="skeleton" style="width: 80px; height: 24px; border-radius: 12px;"></div>
            </td>
            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-2">
                    <div class="skeleton" style="width: 32px; height: 32px; border-radius: 8px;"></div>
                    <div class="skeleton" style="width: 32px; height: 32px; border-radius: 8px;"></div>
                </div>
            </td>
        </tr>
    @endfor
@elseif(is_object($facilities) && method_exists($facilities, 'count') && $facilities->count())
    @foreach ($facilities as $facility)
        @php
            $iconData = $icons[$facility->icon_key] ?? null;
            $iconSvg =
                $iconData['svg'] ??
                '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #9ca3af;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
        @endphp
        <tr>
            <td class="ps-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center bg-transparent border border-gray-200 rounded-circle shrink-0"
                        style="width: 40px; height: 40px;">
                        <div
                            style="color: #f8773c; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;">
                            {!! $iconSvg !!}
                        </div>
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
                <x-shared.status-badge status="{{ $facility->is_active ? 'active' : 'inactive' }}" />
            </td>
            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <x-admin.button type="edit" url="{{ route('admin.facilities.edit', $facility->id) }}"
                        tooltip="Edit Facility" />
                    <x-admin.button type="delete" onclick="openModal('deleteModal{{ $facility->id }}')"
                        tooltip="Delete Facility" />
                </div>
                <x-shared.delete-modal id="deleteModal{{ $facility->id }}" title="Delete Facility"
                    itemName="{{ $facility->name }}" itemType="facility"
                    deleteRoute="{{ route('admin.facilities.destroy', $facility->id) }}" deleteMethod="DELETE" />
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="4" class="text-center py-5 text-muted">
            <div class="d-flex flex-column align-items-center justify-content-center" style="padding: 40px;">
                <div class="bg-muted p-3 rounded-circle border"
                    style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0z" fill="none" />
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path d="M3 21h4L20 8a1.5 1.5 0 0 0-4-4L3 17zM14.5 5.5l4 4" />
                            <path d="M12 8L7 3L3 7l5 5M7 8L5.5 9.5M16 12l5 5l-4 4l-5-5m4 1l-1.5 1.5" />
                        </g>
                    </svg>
                </div>
                <p class="text-muted mt-1">No facilities found</p>
                <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary rounded-pill px-4"
                    style="background: #f8773c; border: none;">
                    Add New Facilities
                </a>
            </div>
        </td>
    </tr>
@endif
