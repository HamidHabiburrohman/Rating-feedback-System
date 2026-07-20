<div class="conv-modal-overlay" id="convEditParticipantModal" hidden>
    <div class="conv-modal-backdrop" data-action="close-edit-modal"></div>
    <div class="conv-modal conv-modal-md">
        <form action="" method="POST" id="convEditParticipantForm">
            @csrf
            @method('PUT')
            <header class="conv-modal-header">
                <h3 class="conv-modal-title">Manage Member</h3>
                <button type="button" class="conv-modal-close" data-action="close-edit-modal">
                    <i class="ti ti-x"></i>
                </button>
            </header>

            <div class="conv-modal-body">
                <div class="conv-profile-summary">
                    <div class="conv-profile-avatar" id="convEditAvatar">U</div>
                    <div class="conv-profile-info">
                        <h4 class="conv-profile-name" id="convEditName">Unknown User</h4>
                        <p class="conv-profile-email" id="convEditEmail">No email provided</p>
                    </div>
                </div>

                <div class="conv-form-group">
                    <label class="conv-label">Permissions & Access</label>
                    <div class="conv-toggle-list">
                        <div class="conv-toggle-item">
                            <div class="conv-toggle-info">
                                <strong>Mute Member</strong>
                                <p>Prevent this member from sending new messages.</p>
                            </div>
                            <label class="conv-switch">
                                <input type="checkbox" name="is_muted" data-action="toggle-permission" data-permission="is_muted">
                                <span class="conv-slider"></span>
                            </label>
                        </div>
                        <div class="conv-toggle-item">
                            <div class="conv-toggle-info">
                                <strong>Read Only</strong>
                                <p>Member can only view the conversation history.</p>
                            </div>
                            <label class="conv-switch">
                                <input type="checkbox" name="is_read_only" data-action="toggle-permission" data-permission="is_read_only">
                                <span class="conv-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="conv-form-group">
                    <label class="conv-label">Conversation Role</label>
                    <div class="conv-custom-dropdown" id="convEditRoleDropdown">
                        <button type="button" class="conv-dropdown-trigger" data-action="toggle-edit-role-dropdown">
                            <span class="conv-dropdown-text" id="convEditRoleText">Member</span>
                            <i class="ti ti-chevron-down"></i>
                        </button>
                        <div class="conv-dropdown-menu">
                            <button type="button" class="conv-dropdown-item" data-action="select-edit-role" data-role="member" data-label="Member">
                                <span>Member</span>
                                <i class="ti ti-check"></i>
                            </button>
                            <button type="button" class="conv-dropdown-item" data-action="select-edit-role" data-role="admin" data-label="Admin">
                                <span>Admin</span>
                                <i class="ti ti-check"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="role" id="convEditSelectedRole" value="member">
                </div>

                <div class="conv-danger-zone">
                    <h4 class="conv-danger-title">
                        <i class="ti ti-alert-triangle"></i> Danger Zone
                    </h4>
                    <div class="conv-danger-item">
                        <div class="conv-danger-info">
                            <strong>Remove from Conversation</strong>
                            <p>This member will lose access to this thread immediately.</p>
                        </div>
                        <button type="button" class="conv-btn-danger-outline" data-action="remove-participant">Remove</button>
                    </div>
                </div>
            </div>

            <footer class="conv-modal-footer">
                <button type="button" class="conv-btn-ghost" data-action="close-edit-modal">Cancel</button>
                <button type="submit" class="conv-btn-primary" data-action="submit-edit">Save Changes</button>
            </footer>
        </form>
    </div>
</div>

@push('styles')
<style>
    .conv-profile-summary {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        margin-bottom: 24px;
    }

    .conv-profile-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #f8773c;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .conv-profile-info {
        min-width: 0;
    }

    .conv-profile-name {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 2px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conv-profile-email {
        font-size: 13px;
        color: #64748b;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conv-toggle-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .conv-toggle-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 14px 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }

    .conv-toggle-info strong {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .conv-toggle-info p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
        line-height: 1.4;
    }

    .conv-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        flex-shrink: 0;
    }

    .conv-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .conv-slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background: #cbd5e1;
        transition: .3s;
        border-radius: 24px;
    }

    .conv-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background: #ffffff;
        transition: .3s;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .conv-switch input:checked+.conv-slider {
        background: #f8773c;
    }

    .conv-switch input:checked+.conv-slider:before {
        transform: translateX(20px);
    }

    .conv-danger-zone {
        margin-top: 24px;
        padding: 20px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 16px;
    }

    .conv-danger-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 700;
        color: #dc2626;
        margin: 0 0 16px 0;
    }

    .conv-danger-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .conv-danger-info strong {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .conv-danger-info p {
        font-size: 13px;
        color: #64748b;
        margin: 0;
    }

    .conv-btn-danger-outline {
        padding: 8px 16px;
        background: transparent;
        border: 1px solid #fca5a5;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #dc2626;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
        font-family: inherit;
    }

    .conv-btn-danger-outline:hover {
        background: #fee2e2;
        border-color: #ef4444;
    }

</style>
@endpush
