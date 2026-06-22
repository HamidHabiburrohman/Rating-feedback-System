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
@elseif(is_object($departments) && method_exists($departments, 'count') && $departments->count())
    @foreach ($departments as $department)
        <tr>
            <td class="ps-4">
                <div class="fw-semibold">{{ $department->name }}</div>
            </td>
            <td>
                <span class="badge rounded-pill bg-light text-dark border px-3">
                    {{ $department->units_count ?? 0 }} units
                </span>
            </td>
            <td>
                <x-shared.status-badge status="{{ $department->is_active ? 'active' : 'inactive' }}" />
            </td>
            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <x-admin.button type="edit" url="{{ route('admin.unit-departments.edit', $department->id) }}"
                        tooltip="Edit Department" />
                    <x-admin.button type="delete" onclick="openModal('deleteModal{{ $department->id }}')"
                        tooltip="Delete Department" />
                </div>
                <x-shared.delete-modal id="deleteModal{{ $department->id }}" title="Delete Department"
                    itemName="{{ $department->name }}" itemType="department"
                    deleteRoute="{{ route('admin.unit-departments.destroy', $department->id) }}"
                    deleteMethod="DELETE" />
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="4" class="text-center py-5 text-muted">
            <div class="d-flex flex-column align-items-center justify-content-center" style="padding: 40px;">
                <div class="bg-muted p-3 rounded-circle border"
                    style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="65px" height="65px" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0z" fill="none" />
                        <g fill="none" fill-rule="evenodd">
                            <path
                                d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z" />
                            <path fill="currentColor"
                                d="M15 6a3 3 0 0 1-2 2.83V11h3a3 3 0 0 1 3 3v1.17a3.001 3.001 0 1 1-2 0V14a1 1 0 0 0-1-1H8a1 1 0 0 0-1 1v1.17a3.001 3.001 0 1 1-2 0V14a3 3 0 0 1 3-3h3V8.83A3.001 3.001 0 1 1 15 6m-3-1a1 1 0 1 0 0 2a1 1 0 0 0 0-2M6 17a1 1 0 1 0 0 2a1 1 0 0 0 0-2m12 0a1 1 0 1 0 0 2a1 1 0 0 0 0-2" />
                        </g>
                    </svg>
                </div>
                <p class="text-muted mt-1">No unit deparments found</p>
                <a href="{{ route('admin.unit-departments.create') }}" class="btn btn-primary rounded-pill px-4"
                    style="background: #f8773c; border: none;">
                    Add New Unit Deparments
                </a>
            </div>
        </td>
    </tr>
@endif
