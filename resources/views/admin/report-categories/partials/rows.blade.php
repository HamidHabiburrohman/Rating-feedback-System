@if ($isLoading ?? false)
    @for ($i = 0; $i < ($skeletonCount ?? 10); $i++)
        <tr>
            <td class="ps-4">
                <div class="skeleton" style="width: 160px; height: 16px;"></div>
            </td>
            <td>
                <div class="skeleton" style="width: 60px; height: 24px; border-radius: 12px;"></div>
            </td>
            <td>
                <div class="skeleton" style="width: 70px; height: 24px; border-radius: 12px;"></div>
            </td>
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
                    <x-admin.button type="edit" url="{{ route('admin.report-categories.edit', $category->id) }}"
                        tooltip="Edit Category" />
                    <x-admin.button type="delete" onclick="openModal('deleteModal{{ $category->id }}')"
                        tooltip="Delete Category" />
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
                <div class="bg-muted p-3 rounded-circle border"
                    style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                    <i class="ti ti-message-report" style="font-size: 40px"></i>
                </div>
                <p class="text-muted mt-3 fw-medium">No report categories found</p>
                <a href="{{ route('admin.report-categories.create') }}" class="btn btn-primary rounded-pill px-4 mt-2"
                    style="background: #f8773c; border: none;">
                    Add New Report Category
                </a>
            </div>
        </td>
    </tr>
@endif
