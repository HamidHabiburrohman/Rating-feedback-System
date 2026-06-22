@if ($isLoading ?? false)
    @for ($i = 0; $i < ($skeletonCount ?? 10); $i++)
        <tr>
            <td class="ps-4"><div class="skeleton" style="width: 160px; height: 16px;"></div></td>
            <td><div class="skeleton" style="width: 60px; height: 24px; border-radius: 12px;"></div></td>
            <td><div class="skeleton" style="width: 70px; height: 24px; border-radius: 12px;"></div></td>
            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-2">
                    <div class="skeleton" style="width: 34px; height: 34px; border-radius: 8px;"></div>
                    <div class="skeleton" style="width: 34px; height: 34px; border-radius: 8px;"></div>
                </div>
            </td>
        </tr>
    @endfor
@elseif (is_object($categories) && method_exists($categories, 'count') && $categories->count())
    @foreach ($categories as $category)
        <tr>
            <td class="ps-4">
                <div class="fw-semibold">{{ $category->name }}</div>
            </td>
            <td>
                <span class="badge rounded-pill bg-light text-dark border px-3">
                    {{ $category->reports_count ?? 0 }} uses
                </span>
            </td>
            <td>
                <x-shared.status-badge status="{{ $category->is_active ? 'active' : 'inactive' }}" />
            </td>
            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <x-admin.button type="edit" url="{{ route('admin.report-categories.edit', $category->id) }}" tooltip="Edit Category" />
                    <x-admin.button type="delete" onclick="openModal('deleteModal{{ $category->id }}')" tooltip="Delete Category" />
                </div>
                <x-shared.delete-modal id="deleteModal{{ $category->id }}" title="Delete Report Category"
                    itemName="{{ $category->name }}" itemType="report category"
                    deleteRoute="{{ route('admin.report-categories.destroy', $category->id) }}" deleteMethod="DELETE" />
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="4" class="text-center py-5 text-muted">
            <div class="d-flex flex-column align-items-center justify-content-center" style="padding: 40px;">
                <div class="bg-muted p-3 rounded-circle border" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M0 0h24v24H0z" fill="none" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v2m0 4.01l.01-.011" />
                        <path d="M3 20.29V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H7.961a2 2 0 0 0-1.561.75l-2.331 2.914A.6.6 0 0 1 3 20.29Z" />
                    </svg>
                </div>
                <p class="text-muted mt-3 fw-medium">No report categories found</p>
                <a href="{{ route('admin.report-categories.create') }}" class="btn btn-primary rounded-pill px-4 mt-2" style="background: #f8773c; border: none;">
                    Add New Report Category
                </a>
            </div>
        </td>
    </tr>
@endif