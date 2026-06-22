@if ($isLoading ?? false)
    @for ($i = 0; $i < ($skeletonCount ?? 10); $i++)
        <tr>
            <td class="ps-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="skeleton skeleton-circle" style="width: 40px; height: 40px;"></div>
                    <div class="skeleton skeleton-text" style="width: 160px; height: 16px;"></div>
                </div>
            </td>
            <td>
                <div class="skeleton skeleton-badge" style="width: 80px; height: 26px;"></div>
            </td>
            <td>
                <div class="skeleton skeleton-badge" style="width: 90px; height: 26px;"></div>
            </td>
            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-2">
                    <div class="skeleton skeleton-btn" style="width: 34px; height: 34px;"></div>
                    <div class="skeleton skeleton-btn" style="width: 34px; height: 34px;"></div>
                </div>
            </td>
        </tr>
    @endfor
@elseif(is_object($types) && method_exists($types, 'count') && $types->count())
    @foreach ($types as $type)
        @php
            $hasUnits = ($type->units_count ?? 0) > 0;
        @endphp
        <tr>
            <td class="ps-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="fw-semibold">{{ $type->name }}</div>
                </div>
            </td>
            <td>
                <span class="badge rounded-pill bg-light text-dark border px-3">
                    {{ $type->units_count ?? 0 }} units
                </span>
            </td>
            <td>
                <x-shared.status-badge status="{{ $type->is_active ? 'active' : 'inactive' }}" />
            </td>
            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <x-admin.button type="edit" url="{{ route('admin.unit-types.edit', $type->id) }}"
                        tooltip="Edit Type" />
                    @if ($hasUnits)
                        <x-admin.button type="delete" disabled="true"
                            disabledTooltip="Tidak dapat dihapus - masih memiliki {{ $type->units_count }} unit"
                            tooltip="Delete Type" />
                    @else
                        <x-admin.button type="delete" onclick="openModal('deleteModal{{ $type->id }}')"
                            tooltip="Delete Type" />
                    @endif
                </div>
                @if (!$hasUnits)
                    <x-shared.delete-modal id="deleteModal{{ $type->id }}" title="Delete Unit Type"
                        itemName="{{ $type->name }}" itemType="unit type"
                        deleteRoute="{{ route('admin.unit-types.destroy', $type->id) }}" deleteMethod="DELETE" />
                @endif
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
                            <path d="M6.5 7.5a1 1 0 1 0 2 0a1 1 0 1 0-2 0" />
                            <path
                                d="M3 6v5.172a2 2 0 0 0 .586 1.414l7.71 7.71a2.41 2.41 0 0 0 3.408 0l5.592-5.592a2.41 2.41 0 0 0 0-3.408l-7.71-7.71A2 2 0 0 0 11.172 3H6a3 3 0 0 0-3 3" />
                        </g>
                    </svg>
                </div>
                <p class="text-muted mt-1">No unit types found</p>
                <a href="{{ route('admin.unit-types.create') }}" class="btn btn-primary rounded-pill px-4"
                    style="background: #f8773c; border: none;">
                    Add New Unit Type
                </a>
            </div>
        </td>
    </tr>
@endif
