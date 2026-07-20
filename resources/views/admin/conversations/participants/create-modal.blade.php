<div class="conv-modal-overlay" id="convCreateParticipantModal" hidden>
    <div class="conv-modal-backdrop" data-action="close-create-modal"></div>
    <div class="conv-modal conv-modal-md">
        <form action="{{ route('admin.conversations.participants.store', $conversation) }}" method="POST" id="convInviteForm">
            @csrf
            <header class="conv-modal-header">
                <h3 class="conv-modal-title">Invite Member</h3>
                <button type="button" class="conv-modal-close" data-action="close-create-modal">
                    <i class="ti ti-x"></i>
                </button>
            </header>

            <div class="conv-modal-body">
                <div class="conv-form-group">
                    <label class="conv-label">Search Member</label>
                    <div class="conv-search-input-wrapper">
                        <i class="ti ti-search conv-search-icon"></i>
                        <input type="text" class="conv-input conv-input-search" placeholder="Search by name or email..." data-action="search-members" autocomplete="off">
                    </div>
                    <div class="conv-search-results" id="convMemberSearchResults">
                        @forelse($availableParticipants ?? [] as $emp)
                        <button type="button" class="conv-search-result-item" data-action="select-member" data-user-id="{{ $emp->id }}" data-user-name="{{ $emp->name }}" data-user-email="{{ $emp->email ?? '' }}">
                            <x-admin.conversations.participant-avatar :participant="$emp" size="sm" />
                            <div class="conv-result-info">
                                <span class="conv-result-name">{{ $emp->name }}</span>
                                <span class="conv-result-meta">{{ $emp->email ?? class_basename($emp) }}</span>
                            </div>
                            <i class="ti ti-check conv-result-check"></i>
                        </button>
                        @empty
                        <div class="conv-search-empty">
                            <i class="ti ti-users-off"></i>
                            <p>No available users found.</p>
                        </div>
                        @endforelse
                    </div>
                    <input type="hidden" name="user_id" id="convSelectedUserId" required>
                </div>

                <div class="conv-form-group">
                    <label class="conv-label">Assign Role</label>
                    <div class="conv-custom-dropdown" id="convRoleDropdown">
                        <button type="button" class="conv-dropdown-trigger" data-action="toggle-role-dropdown">
                            <span class="conv-dropdown-text" id="convRoleText">Member</span>
                            <i class="ti ti-chevron-down"></i>
                        </button>
                        <div class="conv-dropdown-menu">
                            <button type="button" class="conv-dropdown-item is-active" data-action="select-role" data-role="member" data-label="Member">
                                <div class="conv-dropdown-item-info">
                                    <strong>Member</strong>
                                    <span>Can read and send messages.</span>
                                </div>
                                <i class="ti ti-check"></i>
                            </button>
                            <button type="button" class="conv-dropdown-item" data-action="select-role" data-role="admin" data-label="Admin">
                                <div class="conv-dropdown-item-info">
                                    <strong>Admin</strong>
                                    <span>Can manage members and settings.</span>
                                </div>
                                <i class="ti ti-check"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="role" id="convSelectedRole" value="member">
                </div>
            </div>

            <footer class="conv-modal-footer">
                <button type="button" class="conv-btn-ghost" data-action="close-create-modal">Cancel</button>
                <button type="submit" class="conv-btn-primary" data-action="submit-invite" disabled>Send Invite</button>
            </footer>
        </form>
    </div>
</div>

@push('styles')
<style>
    .conv-modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Plus Jakarta Sans', sans-serif;
        padding: 20px;
    }

    .conv-modal-overlay[hidden] {
        display: none;
    }

    .conv-modal-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(4px);
    }

    .conv-modal {
        position: relative;
        width: 100%;
        max-width: 520px;
        max-height: 90vh;
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        z-index: 1;
        overflow: hidden;
    }

    .conv-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 28px 16px;
    }

    .conv-modal-title {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.01em;
    }

    .conv-modal-close {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        border-radius: 8px;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
    }

    .conv-modal-close:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .conv-modal-body {
        padding: 0 28px 24px;
        overflow-y: auto;
        flex: 1;
    }

    .conv-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 16px 28px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }

    .conv-form-group {
        margin-bottom: 24px;
    }

    .conv-form-group:last-child {
        margin-bottom: 0;
    }

    .conv-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }

    .conv-search-input-wrapper {
        position: relative;
    }

    .conv-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 18px;
        pointer-events: none;
    }

    .conv-input {
        width: 100%;
        padding: 12px 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        color: #0f172a;
        transition: all 0.2s;
        font-family: inherit;
    }

    .conv-input-search {
        padding-left: 42px;
    }

    .conv-input:focus {
        outline: none;
        border-color: #f8773c;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(248, 119, 60, 0.1);
    }

    .conv-search-results {
        margin-top: 8px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        max-height: 220px;
        overflow-y: auto;
        background: #ffffff;
    }

    .conv-search-result-item {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        background: transparent;
        border: none;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: background 0.15s;
        text-align: left;
        font-family: inherit;
    }

    .conv-search-result-item:last-child {
        border-bottom: none;
    }

    .conv-search-result-item:hover {
        background: #f8fafc;
    }

    .conv-search-result-item.is-selected {
        background: #fff5f0;
    }

    .conv-result-info {
        flex: 1;
        min-width: 0;
    }

    .conv-result-name {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conv-result-meta {
        display: block;
        font-size: 12px;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conv-result-check {
        font-size: 18px;
        color: #f8773c;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .conv-search-result-item.is-selected .conv-result-check {
        opacity: 1;
    }

    .conv-search-empty {
        padding: 24px;
        text-align: center;
        color: #94a3b8;
    }

    .conv-search-empty i {
        font-size: 24px;
        margin-bottom: 8px;
        display: block;
    }

    .conv-search-empty p {
        margin: 0;
        font-size: 13px;
    }

    .conv-custom-dropdown {
        position: relative;
    }

    .conv-dropdown-trigger {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        color: #0f172a;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }

    .conv-dropdown-trigger:hover {
        border-color: #cbd5e1;
    }

    .conv-dropdown-trigger i {
        font-size: 16px;
        color: #64748b;
        transition: transform 0.2s;
    }

    .conv-custom-dropdown.is-open .conv-dropdown-trigger i {
        transform: rotate(180deg);
    }

    .conv-dropdown-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
        padding: 6px;
        z-index: 50;
        display: none;
    }

    .conv-custom-dropdown.is-open .conv-dropdown-menu {
        display: block;
    }

    .conv-dropdown-item {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        background: transparent;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s;
        text-align: left;
        font-family: inherit;
    }

    .conv-dropdown-item:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .conv-dropdown-item.is-active {
        background: #fff5f0;
        color: #f8773c;
        font-weight: 600;
    }

    .conv-dropdown-item i {
        font-size: 16px;
        color: #f8773c;
        opacity: 0;
    }

    .conv-dropdown-item.is-active i {
        opacity: 1;
    }

    .conv-dropdown-item-info strong {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .conv-dropdown-item-info span {
        font-size: 12px;
        color: #64748b;
        font-weight: 400;
    }

    .conv-btn-ghost {
        padding: 10px 20px;
        background: transparent;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }

    .conv-btn-ghost:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .conv-btn-primary {
        padding: 10px 20px;
        background: #f8773c;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        color: #ffffff;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(248, 119, 60, 0.25);
        font-family: inherit;
    }

    .conv-btn-primary:hover:not(:disabled) {
        background: #ea580c;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(248, 119, 60, 0.35);
    }

    .conv-btn-primary:disabled {
        background: #e2e8f0;
        color: #94a3b8;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

</style>
@endpush
