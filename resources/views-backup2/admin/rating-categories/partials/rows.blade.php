@if(is_object($categories) && method_exists($categories, 'count') && $categories->count())
    @foreach($categories as $category)
        @php
            $statusMap = [
                true => ['label' => 'Active', 'class' => 'bg-success-subtle text-success'],
                false => ['label' => 'Inactive', 'class' => 'bg-danger-subtle text-danger']
            ];
            $status = $statusMap[$category->is_active] ?? $statusMap[true];
        @endphp

        <tr>
            <td class="ps-4">
                <div class="fw-semibold">{{ $category->name }}</div>
            </td>

            <td>
                <span class="badge rounded-pill bg-light text-dark border px-3">
                    {{ $category->sort_order }}
                </span>
            </td>

            <td>
                <span class="badge rounded-pill bg-light text-dark border px-3">
                    {{ $category->rating_scores_count ?? 0 }} uses
                </span>
            </td>

            <td>
                <x-admin.status-badge :status="$status['label']" :class="$status['class']" />
            </td>

            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <x-admin.button type="edit" url="{{ route('admin.rating-categories.edit', $category->id) }}" tooltip="Edit Category" />

                    <x-admin.button type="delete" onclick="openModal('deleteModal{{ $category->id }}')" tooltip="Delete Category" />
                </div>

                <x-admin.delete-modal-component id="deleteModal{{ $category->id }}" title="Delete Rating Category"
                    itemName="{{ $category->name }}" itemType="rating category"
                    deleteRoute="{{ route('admin.rating-categories.destroy', $category->id) }}" deleteMethod="DELETE" />
            </td>
        </tr>
    @endforeach
@elseif(is_string($categories) || $categories === null)
    <tr>
        <td colspan="6" class="text-center py-5 text-muted">
            <div style="padding: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" style="opacity: 0.3; margin-bottom: 16px;">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16v-4" />
                    <circle cx="12" cy="8" r="1" fill="currentColor" />
                </svg>
                <p>Error loading rating categories</p>
                @if(config('app.debug'))
                    <small class="text-danger">Debug: categories is {{ gettype($categories) }}</small>
                @endif
            </div>
        </td>
    </tr>
@else
    <tr>
        <td colspan="6" class="text-center py-5 text-muted">
            <div style="padding: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" style="opacity: 0.3; margin-bottom: 16px;">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16v-4" />
                    <circle cx="12" cy="8" r="1" fill="currentColor" />
                </svg>
                <p>No rating categories found</p>
                <a href="{{ route('admin.rating-categories.seed-defaults') }}"
                    class="btn btn-outline-primary rounded-pill mt-3 px-4"
                    onclick="return confirm('Tambahkan kategori default?')">
                    Add Default Categories
                </a>
            </div>
        </td>
    </tr>
@endif