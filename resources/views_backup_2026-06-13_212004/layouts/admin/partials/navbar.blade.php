@php
    $admin = Auth::guard('admin')->user();
    $adminName = $admin?->nama ?? ($admin?->name ?? 'Admin');
    $adminPhotoUrl = $admin?->photo_url ?? null;
    $adminRole = $admin?->role ?? 'admin';
    $adminInitials = strtoupper(substr($adminName, 0, 2));
@endphp

<nav class="navbar-wrapper">
    <div class="navbar-content">
        <div class="navbar-left">
            <button class="navbar-toggle-btn" id="sidebarToggle" aria-label="Toggle Sidebar">
                <i class="ti ti-layout-sidebar-right-expand" id="toggleIcon"></i>
            </button>
        </div>

        <div class="navbar-center"></div>

        <div class="navbar-right">
            <button class="navbar-icon-btn notification-btn" id="notificationsBtn" aria-label="Notifications">
                <i class="ti ti-bell"></i>
                <span class="navbar-badge" id="notificationBadge">3</span>
            </button>

            <button class="navbar-icon-btn message-btn" id="messagesBtn" aria-label="Messages">
                <i class="ti ti-message-circle"></i>
                <span class="navbar-badge" id="messageBadge">2</span>
            </button>

            <div class="navbar-divider"></div>

            <div class="navbar-user">
                <button class="user-btn" id="userTrigger" aria-expanded="false">
                    <span class="user-avatar">
                        @if ($adminPhotoUrl)
                            <img src="{{ $adminPhotoUrl }}" alt="{{ $adminName }}">
                        @else
                            <span class="avatar-initials">{{ $adminInitials }}</span>
                        @endif
                    </span>
                    <div class="user-info">
                        <span class="user-name">{{ $adminName }}</span>
                        <span class="user-role">
                            @if ($adminRole === 'super_admin')
                                Super Admin
                            @elseif($adminRole === 'admin')
                                Administrator
                            @elseif($adminRole === 'unit')
                                Unit Manager
                            @else
                                {{ ucfirst($adminRole) }}
                            @endif
                        </span>
                    </div>
                    <i class="ti ti-chevron-down"></i>
                </button>

                <div class="user-dropdown" id="userDropdownMenu">
                    <div class="dropdown-header">
                        <div class="dropdown-user">
                            <span class="dropdown-avatar">
                                @if ($adminPhotoUrl)
                                    <img src="{{ $adminPhotoUrl }}" alt="{{ $adminName }}">
                                @else
                                    <span class="avatar-initials">{{ $adminInitials }}</span>
                                @endif
                            </span>
                            <div class="dropdown-user-info">
                                <div class="dropdown-user-name">{{ $adminName }}</div>
                                <div class="dropdown-user-email">{{ $admin->email ?? 'admin@itenas.ac.id' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-items">
                        <a href="{{ route('admin.profile.show') }}" class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span>My Profile</span>
                        </a>
                        <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                            <i class="ti ti-settings"></i>
                            <span>Account Settings</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="ti ti-help"></i>
                            <span>Help & Support</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                            @csrf
                            <button type="submit" class="dropdown-item logout-item">
                                <i class="ti ti-logout"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dropdown-panel notifications-panel" id="notificationsPanel">
        <div class="panel-header">
            <div class="panel-title">
                <h3>Notifications</h3>
                <button class="panel-mark-read" id="markNotificationsRead">Mark all as read</button>
            </div>
        </div>
        <div class="panel-items notifications-list">
            <div class="panel-item notification-item unread">
                <div class="item-icon warning">
                    <i class="ti ti-alert-triangle"></i>
                </div>
                <div class="item-content">
                    <p class="item-text">New report submitted for <strong>Laboratorium Kimia</strong></p>
                    <span class="item-time">5 minutes ago</span>
                </div>
            </div>
            <div class="panel-divider"></div>

            <div class="panel-item notification-item unread">
                <div class="item-icon success">
                    <i class="ti ti-star"></i>
                </div>
                <div class="item-content">
                    <p class="item-text">New rating received for <strong>Perpustakaan</strong> (4.8 ★)</p>
                    <span class="item-time">1 hour ago</span>
                </div>
            </div>
            <div class="panel-divider"></div>

            <div class="panel-item notification-item">
                <div class="item-icon info">
                    <i class="ti ti-user-plus"></i>
                </div>
                <div class="item-content">
                    <p class="item-text">3 new students registered today</p>
                    <span class="item-time">3 hours ago</span>
                </div>
            </div>
            <div class="panel-divider"></div>

            <div class="panel-item notification-item">
                <div class="item-icon primary">
                    <i class="ti ti-building"></i>
                </div>
                <div class="item-content">
                    <p class="item-text">Unit <strong>Ruang Serbaguna</strong> has been updated</p>
                    <span class="item-time">Yesterday</span>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <a href="#">View all notifications</a>
        </div>
    </div>

    <div class="dropdown-panel messages-panel" id="messagesPanel">
        <div class="panel-header">
            <div class="panel-title">
                <h3>Messages</h3>
                <span class="panel-count">2 new</span>
            </div>
        </div>
        <div class="panel-items messages-list">
            <div class="panel-item message-item unread">
                <div class="message-avatar">
                    <span class="avatar-text">AR</span>
                </div>
                <div class="item-content">
                    <div class="message-sender">Ahmad Rizki</div>
                    <p class="item-text">Kapan bisa follow up untuk unit kantor pusat?</p>
                    <span class="item-time">2 minutes ago</span>
                </div>
            </div>
            <div class="panel-divider"></div>

            <div class="panel-item message-item unread">
                <div class="message-avatar">
                    <span class="avatar-text">SN</span>
                </div>
                <div class="item-content">
                    <div class="message-sender">Siti Nurhaliza</div>
                    <p class="item-text">Data fasilitas sudah diupdate, silakan check</p>
                    <span class="item-time">15 minutes ago</span>
                </div>
            </div>
            <div class="panel-divider"></div>

            <div class="panel-item message-item">
                <div class="message-avatar">
                    <span class="avatar-text">RB</span>
                </div>
                <div class="item-content">
                    <div class="message-sender">Rifki Bagus</div>
                    <p class="item-text">Laporan rating sudah siap untuk presentasi</p>
                    <span class="item-time">1 hour ago</span>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <a href="#">View all messages</a>
        </div>
    </div>
</nav>

<style>
    .navbar-wrapper {
        position: sticky;
        top: 0;
        width: 100%;
        height: 70px;
        background: #ffffff;
        border-bottom: 1px solid #edf2f7;
        z-index: 99;
        display: flex;
        align-items: center;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .navbar-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        height: 100%;
        padding: 0 24px;
        gap: 20px;
    }

    .navbar-left {
        display: flex;
        align-items: center;
    }

    .navbar-toggle-btn {
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 10px;
        border-radius: 10px;
        color: #64748b;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .navbar-toggle-btn:hover {
        background: #f1f5f9;
        color: #ff590d;
    }

    .navbar-toggle-btn i {
        transition: transform 0.2s ease-in-out;
    }

    body[data-sidebartype="mini"] .navbar-toggle-btn i {
        transform: rotate(180deg);
    }

    .navbar-center {
        flex: 1;
    }

    .navbar-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .navbar-icon-btn {
        background: rgba(255, 89, 13, 0.08);
        border: none;
        cursor: pointer;
        padding: 10px;
        border-radius: 40px;
        color: #ff590d;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: all 0.2s ease;
        backdrop-filter: blur(4px);
    }

    .navbar-icon-btn:hover {
        background: rgba(255, 89, 13, 0.15);
        transform: scale(1.02);
    }

    .navbar-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        min-width: 18px;
        height: 18px;
        background: #ef4444;
        color: white;
        font-size: 10px;
        font-weight: 700;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .navbar-divider {
        width: 1px;
        height: 30px;
        background: #e2e8f0;
        margin: 0 4px;
    }

    .navbar-user {
        position: relative;
    }

    .user-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 6px 12px 6px 8px;
        border-radius: 40px;
        transition: all 0.2s ease;
    }

    .user-btn:hover {
        background: #f8fafc;
    }

    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #ff590d, #e54a0e);
        flex-shrink: 0;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-initials {
        font-size: 14px;
        font-weight: 600;
        color: white;
    }

    .user-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
        text-align: left;
    }

    .user-name {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.3;
    }

    .user-role {
        font-size: 11px;
        font-weight: 500;
        color: #94a3b8;
        line-height: 1.2;
    }

    .user-btn i {
        font-size: 16px;
        color: #94a3b8;
        transition: transform 0.2s;
    }

    .user-btn[aria-expanded="true"] i {
        transform: rotate(180deg);
    }

    .user-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        min-width: 260px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.1);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s ease;
        z-index: 100;
        overflow: hidden;
    }

    .user-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-header {
        padding: 16px 20px;
        background: #fafbfc;
        border-bottom: 1px solid #f1f5f9;
    }

    .dropdown-user {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .dropdown-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #ff590d, #e54a0e);
        flex-shrink: 0;
    }

    .dropdown-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .dropdown-user-info {
        flex: 1;
    }

    .dropdown-user-name {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .dropdown-user-email {
        font-size: 12px;
        color: #64748b;
        font-weight: 400;
    }

    .dropdown-items {
        padding: 8px 0;
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 20px;
        background: transparent;
        border: none;
        cursor: pointer;
        text-decoration: none;
        color: #334155;
        font-size: 13px;
        font-weight: 500;
        width: 100%;
        transition: all 0.2s;
    }

    .dropdown-item:hover {
        background: #fef6f0;
        color: #ff590d;
        padding: 10px 20px;
        width: 100%;
        gap: 12px;
    }

    .dropdown-item i {
        font-size: 18px;
        color: #94a3b8;
        flex-shrink: 0;
        transition: color 0.2s;
    }

    .dropdown-item:hover i {
        color: #ff590d;
    }

    .logout-item {
        color: #ef4444;
    }

    .logout-item i {
        color: #ef4444;
    }

    .logout-item:hover {
        background: #fef2f2;
        color: #dc2626;
    }

    .logout-item:hover i {
        color: #dc2626;
    }

    .dropdown-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 8px 0;
    }

    .logout-form {
        width: 100%;
        margin: 0;
    }

    .dropdown-panel {
        position: absolute;
        top: 70px;
        width: 380px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.1);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s ease;
        z-index: 100;
        max-height: 520px;
        display: flex;
        flex-direction: column;
    }

    .dropdown-panel.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .notifications-panel {
        right: 80px;
    }

    .messages-panel {
        right: 130px;
    }

    .panel-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        flex-shrink: 0;
    }

    .panel-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .panel-title h3 {
        font-size: 15px;
        font-weight: 700;
        margin: 0;
        color: #0f172a;
    }

    .panel-mark-read {
        background: transparent;
        border: none;
        color: #ff590d;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: color 0.2s;
    }

    .panel-mark-read:hover {
        color: #e54a0e;
    }

    .panel-count {
        font-size: 12px;
        font-weight: 600;
        color: #ff590d;
        background: #fff5f0;
        padding: 2px 10px;
        border-radius: 20px;
    }

    .panel-items {
        flex: 1;
        overflow-y: auto;
    }

    .panel-items::-webkit-scrollbar {
        width: 4px;
    }

    .panel-items::-webkit-scrollbar-track {
        background: transparent;
    }

    .panel-items::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .panel-item {
        display: flex;
        gap: 12px;
        padding: 14px 20px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .panel-item:hover {
        background: #fafafa;
    }

    .notification-item.unread,
    .message-item.unread {
        background: #fffaf7;
    }

    .panel-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 0;
    }

    .item-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
    }

    .item-icon.warning {
        background: #fef3c7;
        color: #f59e0b;
    }

    .item-icon.success {
        background: #d1fae5;
        color: #10b981;
    }

    .item-icon.info {
        background: #dbeafe;
        color: #3b82f6;
    }

    .item-icon.primary {
        background: #fff5f0;
        color: #ff590d;
    }

    .message-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        flex-shrink: 0;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
    }

    .item-content {
        flex: 1;
        min-width: 0;
    }

    .item-text {
        font-size: 13px;
        color: #334155;
        margin: 0 0 4px 0;
        line-height: 1.4;
    }

    .notification-item.unread .item-text,
    .message-item.unread .item-text {
        font-weight: 600;
        color: #0f172a;
    }

    .item-text strong {
        color: #0f172a;
    }

    .message-sender {
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .item-time {
        font-size: 11px;
        color: #94a3b8;
    }

    .panel-footer {
        padding: 12px 20px;
        border-top: 1px solid #f1f5f9;
        text-align: center;
        flex-shrink: 0;
    }

    .panel-footer a {
        color: #ff590d;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
    }

    .panel-footer a:hover {
        color: #e54a0e;
    }

    @media (max-width: 768px) {
        .navbar-content {
            padding: 0 16px;
            gap: 12px;
        }

        .user-info {
            display: none;
        }

        .navbar-divider {
            display: none;
        }

        .dropdown-panel {
            right: 16px;
            left: 16px;
            width: auto;
        }

        .notifications-panel {
            right: 16px;
        }

        .messages-panel {
            right: 16px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const userTrigger = document.getElementById('userTrigger');
        const userDropdown = document.getElementById('userDropdownMenu');
        const notificationsBtn = document.getElementById('notificationsBtn');
        const notificationsPanel = document.getElementById('notificationsPanel');
        const messagesBtn = document.getElementById('messagesBtn');
        const messagesPanel = document.getElementById('messagesPanel');
        const markReadBtn = document.getElementById('markNotificationsRead');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                const currentType = document.body.getAttribute('data-sidebartype');
                const newType = currentType === 'mini' ? 'full' : 'mini';
                document.body.setAttribute('data-sidebartype', newType);
                localStorage.setItem('sidebarType', newType);
            });
        }

        if (userTrigger && userDropdown) {
            userTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                const isExpanded = userTrigger.getAttribute('aria-expanded') === 'true';
                userTrigger.setAttribute('aria-expanded', !isExpanded);
                userDropdown.classList.toggle('show');
                notificationsPanel.classList.remove('show');
                messagesPanel.classList.remove('show');
            });
        }

        if (notificationsBtn && notificationsPanel) {
            notificationsBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationsPanel.classList.toggle('show');
                messagesPanel.classList.remove('show');
                userDropdown.classList.remove('show');
                userTrigger.setAttribute('aria-expanded', 'false');
            });
        }

        if (messagesBtn && messagesPanel) {
            messagesBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                messagesPanel.classList.toggle('show');
                notificationsPanel.classList.remove('show');
                userDropdown.classList.remove('show');
                userTrigger.setAttribute('aria-expanded', 'false');
            });
        }

        if (markReadBtn) {
            markReadBtn.addEventListener('click', function() {
                document.querySelectorAll('.notification-item.unread').forEach(item => {
                    item.classList.remove('unread');
                });
                const badge = document.getElementById('notificationBadge');
                if (badge) {
                    badge.style.display = 'none';
                }
            });
        }

        document.addEventListener('click', function(e) {
            if (userDropdown && !userDropdown.contains(e.target) && !userTrigger.contains(e.target)) {
                userDropdown.classList.remove('show');
                userTrigger.setAttribute('aria-expanded', 'false');
            }
            if (notificationsPanel && !notificationsPanel.contains(e.target) && !notificationsBtn.contains(e.target)) {
                notificationsPanel.classList.remove('show');
            }
            if (messagesPanel && !messagesPanel.contains(e.target) && !messagesBtn.contains(e.target)) {
                messagesPanel.classList.remove('show');
            }
        });
    });
</script>