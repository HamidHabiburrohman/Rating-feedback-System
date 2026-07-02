@if ($isLoading ?? false)
    @for ($i = 0; $i < ($skeletonCount ?? 10); $i++)
        <tr>
            <td class="ps-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="skeleton" style="width: 40px; height: 40px; border-radius: 8px;"></div>
                    <div class="skeleton" style="width: 120px; height: 16px;"></div>
                </div>
            </td>
            <td>
                <div class="skeleton" style="width: 140px; height: 16px;"></div>
            </td>
            <td>
                <div class="skeleton" style="width: 80px; height: 24px; border-radius: 12px;"></div>
            </td>
            <td>
                <div class="skeleton" style="width: 100px; height: 16px;"></div>
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
@elseif(is_object($qrCodes) && method_exists($qrCodes, 'count') && $qrCodes->count())
    @foreach ($qrCodes as $qrCode)
        @php
            $isExpired = $qrCode->expires_at && $qrCode->expires_at->isPast();
            $statusClass = $qrCode->is_active && !$isExpired ? 'success' : ($isExpired ? 'warning' : 'secondary');
            $statusText = $qrCode->is_active && !$isExpired ? 'Active' : ($isExpired ? 'Expired' : 'Inactive');
        @endphp
        <tr>
            <td class="ps-4">
                <div class="d-flex align-items-center gap-3">
                    @if($qrCode->qr_image_path)
                        <img src="{{ asset('storage/' . $qrCode->qr_image_path) }}" alt="QR Code"
                            style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover; border: 1px solid #e5e7eb;">
                    @else
                        <div
                            style="width: 40px; height: 40px; border-radius: 50%; background: #fff5f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f8773c" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                                <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                                <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <div class="fw-semibold" style="font-family: 'SF Mono', monospace; font-size: 0.8rem;">
                            {{ Str::limit($qrCode->code, 12) }}
                        </div>
                    </div>
                </div>
            </td>
            <td>
                @if ($qrCode->unit)
                    <span class="fw-medium">{{ $qrCode->unit->name }}</span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }}-emphasis rounded-pill px-3 py-2">
                    {{ $statusText }}
                </span>
            </td>
            <td>
                <span class="text-muted" style="font-size: 0.875rem;">
                    @if($qrCode->expires_at)
                        {{ $qrCode->expires_at->format('M d, Y') }}
                    @else
                        No expiration
                    @endif
                </span>
            </td>
            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">

                    <button type="button" class="btn btn-sm btn-light rounded-circle open-preview-modal-btn"
                        data-id="{{ $qrCode->id }}" data-bs-toggle="tooltip" title="Preview QR Code">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>

                    @if($qrCode->qr_image_path)
                        <x-admin.button type="download" url="{{ route('admin.qr-codes.download', $qrCode->id) }}"
                            tooltip="Download QR Code" />
                    @endif

                    <form id="regenerate-form-{{ $qrCode->id }}" action="{{ route('admin.qr-codes.regenerate', $qrCode->id) }}"
                        method="POST" class="d-none">
                        @csrf
                    </form>
                    <x-admin.button type="regenerate"
                        onclick="if(confirm('Are you sure you want to regenerate this QR Code? This will invalidate the old one.')) document.getElementById('regenerate-form-{{ $qrCode->id }}').submit();"
                        tooltip="Regenerate QR Code" />

                    @if($qrCode->is_active)
                        <form id="deactivate-form-{{ $qrCode->id }}" action="{{ route('admin.qr-codes.deactivate', $qrCode->id) }}"
                            method="POST" class="d-none">
                            @csrf
                            @method('PATCH')
                        </form>
                        <x-admin.button type="deactivate"
                            onclick="if(confirm('Are you sure you want to deactivate this QR Code?')) document.getElementById('deactivate-form-{{ $qrCode->id }}').submit();"
                            tooltip="Deactivate QR Code" />
                    @else
                        <form id="activate-form-{{ $qrCode->id }}" action="{{ route('admin.qr-codes.activate', $qrCode->id) }}"
                            method="POST" class="d-none">
                            @csrf
                            @method('PATCH')
                        </form>
                        <x-admin.button type="activate"
                            onclick="if(confirm('Are you sure you want to activate this QR Code?')) document.getElementById('activate-form-{{ $qrCode->id }}').submit();"
                            tooltip="Activate QR Code" />
                    @endif

                    <x-admin.button type="delete" onclick="openModal('deleteModal{{ $qrCode->id }}')"
                        tooltip="Delete QR Code" />
                </div>

                <x-shared.delete-modal id="deleteModal{{ $qrCode->id }}" title="Delete QR Code" itemName="{{ $qrCode->code }}"
                    itemType="qr_code" deleteRoute="{{ route('admin.qr-codes.destroy', $qrCode->id) }}" deleteMethod="DELETE" />
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center py-5 text-muted">
            <div class="d-flex flex-column align-items-center justify-content-center" style="padding: 40px;">
                <div
                    style="width: 70px; height: 70px; border-radius: 50%; background: #fff5f0; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                        stroke="#f8773c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                        <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                        <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                    </svg>
                </div>
                <p class="text-muted mt-3 fw-medium">No QR codes found</p>
                <button type="button" x-on:click="$dispatch('open-generate-modal')"
                    class="btn btn-primary rounded-pill px-4 mt-2" style="background: #f8773c; border: none; color: white;">
                    Generate First QR Code
                </button>
            </div>
        </td>
    </tr>
@endif