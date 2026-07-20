{{-- resources/views/admin/conversations/participants/table.blade.php --}}
<div class="conv-members-panel" id="convMembersPanel">
    <header class="conv-members-header">
        <div class="conv-members-header-left">
            <h2 class="conv-members-title">Members</h2>
            <span class="conv-members-count">{{ $participants->count() }} {{ Str::plural('participant', $participants->count()) }}</span>
        </div>
        <div class="conv-members-header-right">
            <button type="button" class="conv-btn-primary" data-action="open-create-participant">
                <i class="ti ti-user-plus"></i>
                <span>Invite Member</span>
            </button>
        </div>
    </header>

    <div class="conv-members-list">
        @forelse($participants as $participant)
            @php
                $user = $participant->participant;
                $isOnline = $user->is_online ?? false;
                $role = $participant->role ?? 'member';
                $roleConfig = [
                    'admin' => ['label' => 'Admin', 'class' => 'conv-role-admin'],
                    'moderator' => ['label' => 'Moderator', 'class' => 'conv-role-moderator'],
                    'member' => ['label' => 'Member', 'class' => 'conv-role-member'],
                ][$role] ?? ['label' => ucfirst($role), 'class' => 'conv-role-member'];
            @endphp
            
            <div class="conv-member-card" data-participant-id="{{ $participant->id }}">
                <div class="conv-member-card-left">
                    <x-admin.conversations.participant-avatar 
                        :participant="$user" 
                        size="md" 
                        :show-status="true" 
                        :status="$isOnline ? 'online' : 'offline'" 
                    />
                    <div class="conv-member-card-info">
                        <div class="conv-member-card-name">
                            {{ $user->name ?? 'Unknown User' }}
                            @if($user->id === auth('admin')->id())
                                <span class="conv-member-you">(You)</span>
                            @endif
                        </div>
                        <div class="conv-member-card-email">{{ $user->email ?? class_basename($participant->participant_type) }}</div>
                    </div>
                </div>
                
                <div class="conv-member-card-right">
                    <span class="conv-role-badge {{ $roleConfig['class'] }}">
                        {{ $roleConfig['label'] }}
                    </span>
                    
                    @if($user->id !== auth('admin')->id())
                        <div class="conv-member-actions">
                            <button type="button" 
                                    class="conv-member-action-btn" 
                                    data-action="manage-participant" 
                                    data-participant-id="{{ $participant->id }}"
                                    data-user-id="{{ $user->id }}"
                                    data-name="{{ addslashes($user->name ?? 'Unknown') }}"
                                    data-email="{{ addslashes($user->email ?? '') }}"
                                    data-role="{{ $role }}"
                                    data-is-muted="{{ ($participant->is_muted ?? false) ? 'true' : 'false' }}"
                                    data-is-read-only="{{ ($participant->is_read_only ?? false) ? 'true' : 'false' }}"
                                    title="Manage Member">
                                <i class="ti ti-settings"></i>
                            </button>
                            <button type="button" 
                                    class="conv-member-action-btn conv-action-danger" 
                                    data-action="remove-participant" 
                                    data-participant-id="{{ $participant->id }}" 
                                    title="Remove Member">
                                <i class="ti ti-user-minus"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="conv-members-empty">
                <x-admin.conversations.empty-state 
                    icon="ti-users" 
                    title="No members yet" 
                    description="Invite members to start collaborating in this conversation." 
                    iconColor="#f8773c" 
                    iconBg="#fff5f0" 
                    :compact="true"
                />
            </div>
        @endforelse
    </div>
</div>

@once
@push('styles')
<style>
.conv-members-panel {
    font-family: 'Plus Jakarta Sans', sans-serif;
    display: flex;
    flex-direction: column;
    height: 100%;
    background: #ffffff;
}

.conv-members-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 32px;
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    flex-shrink: 0;
}

.conv-members-header-left {
    display: flex;
    align-items: baseline;
    gap: 12px;
}

.conv-members-title {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.02em;
}

.conv-members-count {
    font-size: 14px;
    font-weight: 500;
    color: #64748b;
}

.conv-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 16px;
    background: #f8773c;
    color: #ffffff;
    border: none;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(248, 119, 60, 0.2);
    font-family: inherit;
}

.conv-btn-primary:hover {
    background: #ea580c;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(248, 119, 60, 0.3);
}

.conv-btn-primary i {
    font-size: 16px;
    stroke-width: 2;
}

.conv-members-list {
    flex: 1;
    overflow-y: auto;
    padding: 16px 32px 32px;
}

.conv-member-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px;
    border: 1px solid rgba(15, 23, 42, 0.06);
    border-radius: 16px;
    margin-bottom: 12px;
    transition: all 0.2s ease;
    background: #ffffff;
}

.conv-member-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
}

.conv-member-card-left {
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
    flex: 1;
}

.conv-member-card-info {
    min-width: 0;
}

.conv-member-card-name {
    font-size: 15px;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 2px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.conv-member-you {
    font-size: 12px;
    font-weight: 500;
    color: #94a3b8;
    background: #f1f5f9;
    padding: 2px 6px;
    border-radius: 4px;
}

.conv-member-card-email {
    font-size: 13px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conv-member-card-right {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-shrink: 0;
}

.conv-role-badge {
    display: inline-flex;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.conv-role-admin { background: #eff6ff; color: #2563eb; }
.conv-role-member { background: #f1f5f9; color: #475569; }
.conv-role-moderator { background: #faf5ff; color: #9333ea; }

.conv-member-actions {
    display: flex;
    gap: 8px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.conv-member-card:hover .conv-member-actions {
    opacity: 1;
}

.conv-member-action-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: 1px solid transparent;
    border-radius: 8px;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
}

.conv-member-action-btn:hover {
    background: #f1f5f9;
    border-color: #e2e8f0;
    color: #0f172a;
}

.conv-member-action-btn.conv-action-danger:hover {
    background: #fef2f2;
    border-color: #fecaca;
    color: #dc2626;
}

.conv-member-action-btn i {
    font-size: 18px;
    stroke-width: 1.5;
}

.conv-members-empty {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    min-height: 300px;
}

@media (max-width: 768px) {
    .conv-members-header,
    .conv-members-list {
        padding-left: 16px;
        padding-right: 16px;
    }
    
    .conv-member-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .conv-member-card-right {
        width: 100%;
        justify-content: space-between;
    }
    
    .conv-member-actions {
        opacity: 1;
    }
}
</style>
@endpush
@endonce