@if(is_object($units) && method_exists($units, 'count') && $units->count())
    @foreach($units as $unit)
        @php
            $deletedAt = $unit->deleted_at ? $unit->deleted_at->format('d M Y H:i') : '-';
            $deletedAtDiff = $unit->deleted_at ? $unit->deleted_at->diffForHumans() : '-';
        @endphp

        <tr>
            <td class="ps-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 overflow-hidden d-flex align-items-center justify-content-center"
                         style="width: 48px; height: 48px; background: #f8f9fa; border: 1px solid #e9ecef;">
                        @if($unit->primaryPhoto)
                            <img src="{{ asset('storage/' . $unit->primaryPhoto->thumbnail_path) }}" 
                                 alt="{{ $unit->name }}" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <span class="fw-bold" style="color: #f8773c;">{{ substr($unit->name, 0, 2) }}</span>
                        @endif
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $unit->name }}</div>
                        <div class="small text-muted">{{ $unit->code }}</div>
                    </div>
                </div>
            </td>

            <td>
                <span class="badge rounded-pill bg-light text-dark border px-3">
                    {{ $unit->type->name ?? '-' }}
                </span>
            </td>

            <td class="text-center">
                <div class="d-flex flex-column align-items-center">
                    <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="me-1">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        {{ $deletedAt }}
                    </span>
                    <small class="text-muted mt-1">{{ $deletedAtDiff }}</small>
                </div>
            </td>

            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <!-- Restore Button -->
                    <form action="{{ route('admin.units.restore', $unit->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" 
                                class="btn btn-sm btn-outline-success rounded-circle d-flex align-items-center justify-content-center action-btn"
                                data-bs-toggle="tooltip" 
                                data-bs-title="Restore Unit"
                                onclick="return confirm('Restore this unit?')"
                                style="width: 36px; height: 36px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                <path d="M3 3v5h5"/>
                            </svg>
                        </button>
                    </form>

                    <!-- Force Delete Button -->
                    <form action="{{ route('admin.units.force-delete', $unit->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center action-btn btn-trigger-delete"
                                data-bs-toggle="tooltip" 
                                data-bs-title="Delete Permanently"
                                onclick="return confirm('Permanently delete this unit? This action cannot be undone.')"
                                style="width: 36px; height: 36px;">
                            <div class="pulse-ring"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                        </button>
                    </form>
                </div>
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
                <p>Error loading trashed units</p>
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
                <p>No trashed units found</p>
                <a href="{{ route('admin.units.index') }}" class="btn btn-primary rounded-pill mt-3 px-4">
                    Back to Units
                </a>
            </div>
        </td>
    </tr>
@endif