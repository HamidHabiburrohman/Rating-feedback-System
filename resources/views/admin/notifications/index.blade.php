@extends('layouts.admin.app')

@section('title', 'Notifications')

@push('styles')
    <style>
        :root {
            --primary: #f8773c;
            --primary-light: rgba(248, 119, 60, 0.08);
            --primary-border: rgba(248, 119, 60, 0.15);
            --bg-surface: #ffffff;
            --text-main: #0f172a;
            --text-secondary: #64748b;
            --text-tertiary: #94a3b8;
            --border-subtle: rgba(15, 23, 42, 0.06);
            --shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.04);
            --shadow-md: 0 4px 12px rgba(15, 23, 42, 0.06);
            --shadow-lg: 0 12px 32px rgba(15, 23, 42, 0.08);
            --shadow-unread: 0 0 0 1px rgba(248, 119, 60, 0.15), 0 12px 32px rgba(248, 119, 60, 0.08);
            --radius-lg: 16px;
            --radius-xl: 24px;
            --radius-full: 9999px;
        }

        .notif-app {
            max-width: 1400px;
            margin: 0 auto;
            padding: 48px 32px;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: var(--text-main);
            min-height: 100vh;
        }

        .notif-hero {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 48px;
            gap: 24px;
        }

        .notif-title {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0 0 8px;
            color: var(--text-main);
        }

        .notif-desc {
            font-size: 15px;
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.5;
        }

        .notif-badge {
            display: none;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 10px;
            background: var(--primary);
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            border-radius: var(--radius-full);
            box-shadow: 0 4px 12px rgba(248, 119, 60, 0.25);
        }

        .notif-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-bottom: 48px;
        }

        .stat-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-xl);
            padding: 28px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-indicator {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--primary);
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-main);
            line-height: 1;
        }

        .notif-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
            margin-bottom: 24px;
        }

        .notif-search {
            flex: 1;
            max-width: 400px;
            position: relative;
        }

        .notif-search input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border-radius: var(--radius-full);
            border: 1px solid var(--border-subtle);
            background: var(--bg-surface);
            font-size: 14px;
            color: var(--text-main);
            outline: none;
            transition: all 0.2s;
            font-family: inherit;
        }

        .notif-search input::placeholder {
            color: var(--text-tertiary);
        }

        .notif-search input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-light);
        }

        .notif-search svg {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-tertiary);
            pointer-events: none;
        }

        .notif-actions {
            display: flex;
            gap: 12px;
        }

        .btn-action {
            padding: 10px 20px;
            border-radius: var(--radius-full);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--border-subtle);
            background: var(--bg-surface);
            color: var(--text-main);
            font-family: inherit;
        }

        .btn-action:hover:not(:disabled) {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .btn-action:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-action.primary {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .btn-action.primary:hover:not(:disabled) {
            background: #ea6a2e;
            border-color: #ea6a2e;
        }

        .notif-filters {
            display: inline-flex;
            background: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-full);
            padding: 0.5rem;
            gap: 4px;
            margin-bottom: 32px;
            box-shadow: var(--shadow-sm);
        }

        .filter-pill {
            padding: 8px 20px;
            border-radius: var(--radius-full);
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }

        .filter-pill:hover:not(.active) {
            color: var(--text-main);
            background: #f8fafc;
        }

        .filter-pill.active {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(248, 119, 60, 0.25);
        }

        .notif-feed {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .notif-item {
            background: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-xl);
            padding: 24px;
            display: flex;
            gap: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
        }

        .notif-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .notif-item.unread {
            box-shadow: var(--shadow-unread);
            border-color: transparent;
        }

        .notif-item.unread:hover {
            box-shadow: var(--shadow-unread), var(--shadow-md);
        }

        .unread-dot {
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            position: absolute;
            top: 24px;
            right: 24px;
            box-shadow: 0 0 0 4px rgba(248, 119, 60, 0.15);
        }

        .notif-icon-wrap {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 24px;
        }

        .icon-rating {
            background: #fef3c7;
            color: #d97706;
        }

        .icon-report {
            background: #dbeafe;
            color: #2563eb;
        }

        .icon-critical {
            background: #fee2e2;
            color: #dc2626;
        }

        .icon-info {
            background: #f1f5f9;
            color: #475569;
        }

        .notif-content {
            flex: 1;
            min-width: 0;
        }

        .notif-title-text {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-main);
            margin: 0;
            line-height: 1.4;
        }

        .notif-msg {
            font-size: 14px;
            font-weight: 400;
            color: var(--text-secondary);
            margin: 4px 0 0;
            line-height: 1.5;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .notif-time {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-tertiary);
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .skel-item {
            background: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-xl);
            padding: 24px;
            display: flex;
            gap: 20px;
        }

        .skel-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: #f1f5f9;
            animation: pulse 1.5s infinite;
        }

        .skel-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .skel-line {
            height: 14px;
            background: #f1f5f9;
            border-radius: 8px;
            animation: pulse 1.5s infinite;
        }

        .skel-line.w-40 {
            width: 40%;
        }

        .skel-line.w-80 {
            width: 80%;
        }

        .skel-line.w-20 {
            width: 20%;
            height: 12px;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .notif-state {
            text-align: center;
            padding: 80px 24px;
            background: var(--bg-surface);
            border: 1px dashed var(--border-subtle);
            border-radius: var(--radius-xl);
        }

        .notif-state-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 16px;
            background: #f8fafc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-tertiary);
            font-size: 28px;
        }

        .notif-state-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-main);
            margin: 0 0 8px;
        }

        .notif-state-desc {
            font-size: 14px;
            color: var(--text-secondary);
            margin: 0;
        }

        @media (max-width: 1024px) {
            .notif-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .notif-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .notif-search {
                max-width: 100%;
            }

            .notif-actions {
                justify-content: flex-end;
            }
        }

        @media (max-width: 640px) {
            .notif-app {
                padding: 24px 16px;
            }

            .notif-stats {
                grid-template-columns: 1fr;
            }

            .notif-hero {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .notif-item {
                flex-direction: column;
                gap: 16px;
                padding: 20px;
            }

            .notif-icon-wrap {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                font-size: 20px;
            }

            .unread-dot {
                top: 20px;
                right: 20px;
            }

            .notif-filters {
                overflow-x: auto;
                width: 100%;
                justify-content: flex-start;
            }

            .filter-pill {
                white-space: nowrap;
                flex-shrink: 0;
            }

            .notif-actions {
                flex-direction: column;
            }

            .btn-action {
                justify-content: center;
            }
        }
    </style>
@endpush

@section('admin-content')
    <div class="notif-app">
        <header class="notif-hero">
            <div class="notif-hero-left">
                <h1 class="notif-title">Notifications</h1>
                <p class="notif-desc">Stay updated with the latest activities across your units, ratings, and reports.</p>
            </div>
            <div class="notif-hero-right">
                <span class="notif-badge" id="unreadBadge">0</span>
            </div>
        </header>

        <section class="notif-stats">
            <div class="stat-card">
                <span class="stat-label"><span class="stat-indicator"></span> Unread</span>
                <span class="stat-value" id="statUnread">0</span>
            </div>
            <div class="stat-card">
                <span class="stat-label"><span class="stat-indicator" style="background: #d97706;"></span> Ratings
                    Activity</span>
                <span class="stat-value" id="statRatings">0</span>
            </div>
            <div class="stat-card">
                <span class="stat-label"><span class="stat-indicator" style="background: #2563eb;"></span> Reports</span>
                <span class="stat-value" id="statReports">0</span>
            </div>
            <div class="stat-card">
                <span class="stat-label"><span class="stat-indicator" style="background: #dc2626;"></span> Critical
                    Alerts</span>
                <span class="stat-value" id="statCritical">0</span>
            </div>
        </section>

        <div class="notif-toolbar">
            <div class="notif-search">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
                <input type="text" id="searchInput" placeholder="Search notifications...">
            </div>
            <div class="notif-actions">
                <button class="btn-action" id="refreshBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                        <path d="M3 3v5h5"></path>
                        <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"></path>
                        <path d="M16 16h5v5"></path>
                    </svg>
                    Refresh
                </button>
                <button class="btn-action primary" id="markAllBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Mark all as read
                </button>
            </div>
        </div>

        <div class="notif-filters" id="filterControl">
            <button class="filter-pill active" data-filter="all">All</button>
            <button class="filter-pill" data-filter="unread">Unread</button>
            <button class="filter-pill" data-filter="ratings">Ratings</button>
            <button class="filter-pill" data-filter="reports">Reports</button>
            <button class="filter-pill" data-filter="critical">Critical</button>
        </div>

        <section class="notif-feed" id="notifFeed"></section>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const API_LATEST = "{{ route('admin.notifications.latest') }}";
            const API_MARK_ALL = "{{ route('admin.notifications.mark-all-read') }}";
            const CSRF = "{{ csrf_token() }}";

            const feedEl = document.getElementById('notifFeed');
            const searchEl = document.getElementById('searchInput');
            const refreshBtn = document.getElementById('refreshBtn');
            const markAllBtn = document.getElementById('markAllBtn');
            const filterControl = document.getElementById('filterControl');
            const badgeEl = document.getElementById('unreadBadge');

            let allNotifications = [];
            let currentFilter = 'all';
            let currentSearch = '';

            function escapeHtml(text) {
                if (text === null || text === undefined) return '';
                const div = document.createElement('div');
                div.textContent = String(text);
                return div.innerHTML;
            }

            function getTitle(n) {
                if (n.title) return n.title;
                if (n.data && n.data.title) return n.data.title;
                const labels = {
                    rating: 'New Rating Received',
                    success: 'New Rating Received',
                    report: 'New Report Submitted',
                    warning: 'New Report Submitted',
                    critical: 'Critical Alert',
                    error: 'Critical Alert',
                    info: 'System Notification'
                };
                return labels[n.type] || 'Notification';
            }

            function getMessage(n) {
                if (n.message) return n.message;
                if (n.body) return n.body;
                if (n.data && n.data.message) return n.data.message;
                if (n.data && n.data.body) return n.data.body;
                return '';
            }

            function getIconMeta(type) {
                if (type === 'rating' || type === 'success') return {
                    class: 'icon-rating',
                    icon: 'ti-star-filled'
                };
                if (type === 'critical' || type === 'error') return {
                    class: 'icon-critical',
                    icon: 'ti-alert-circle-filled'
                };
                if (type === 'report' || type === 'warning') return {
                    class: 'icon-report',
                    icon: 'ti-message-report'
                };
                return {
                    class: 'icon-info',
                    icon: 'ti-bell'
                };
            }

            function showSkeleton() {
                feedEl.innerHTML = Array(4).fill('').map(() => `
            <div class="skel-item">
                <div class="skel-icon"></div>
                <div class="skel-content">
                    <div class="skel-line w-40"></div>
                    <div class="skel-line w-80"></div>
                    <div class="skel-line w-20"></div>
                </div>
            </div>
        `).join('');
            }

            function showEmpty() {
                feedEl.innerHTML = `
            <div class="notif-state">
                <div class="notif-state-icon"><i class="ti ti-bell-off"></i></div>
                <h3 class="notif-state-title">You're all caught up</h3>
                <p class="notif-state-desc">No notifications match your current filters.</p>
            </div>
        `;
            }

            function showError() {
                feedEl.innerHTML = `
            <div class="notif-state">
                <div class="notif-state-icon"><i class="ti ti-alert-triangle"></i></div>
                <h3 class="notif-state-title">Unable to load notifications</h3>
                <p class="notif-state-desc">Please try refreshing the page.</p>
            </div>
        `;
            }

            function updateBadge() {
                const count = allNotifications.filter(n => !n.read_at).length;
                if (count > 0) {
                    badgeEl.textContent = count > 99 ? '99+' : count;
                    badgeEl.style.display = 'flex';
                } else {
                    badgeEl.style.display = 'none';
                }
            }

            function updateStats() {
                document.getElementById('statUnread').textContent = allNotifications.filter(n => !n.read_at).length;
                document.getElementById('statRatings').textContent = allNotifications.filter(n => n.type === 'rating' ||
                    n.type === 'success').length;
                document.getElementById('statReports').textContent = allNotifications.filter(n => n.type === 'report' ||
                    n.type === 'warning').length;
                document.getElementById('statCritical').textContent = allNotifications.filter(n => n.type ===
                    'critical' || n.type === 'error').length;
            }

            function renderFeed(items) {
                if (!items || items.length === 0) {
                    showEmpty();
                    return;
                }

                feedEl.innerHTML = items.map(n => {
                    const meta = getIconMeta(n.type);
                    const title = escapeHtml(getTitle(n));
                    const msg = escapeHtml(getMessage(n));
                    const isUnread = !n.read_at;

                    return `
                <article class="notif-item ${isUnread ? 'unread' : ''}">
                    ${isUnread ? '<span class="unread-dot"></span>' : ''}
                    <div class="notif-icon-wrap ${meta.class}">
                        <i class="ti ${meta.icon}"></i>
                    </div>
                    <div class="notif-content">
                        <h3 class="notif-title-text">${title}</h3>
                        ${msg ? `<p class="notif-msg">${msg}</p>` : ''}
                        <span class="notif-time">
                            <i class="ti ti-clock"></i> ${escapeHtml(n.time || '')}
                        </span>
                    </div>
                </article>
            `;
                }).join('');
            }

            function filterNotifications() {
                let filtered = allNotifications;

                if (currentFilter === 'unread') filtered = filtered.filter(n => !n.read_at);
                else if (currentFilter === 'ratings') filtered = filtered.filter(n => n.type === 'rating' || n.type ===
                    'success');
                else if (currentFilter === 'reports') filtered = filtered.filter(n => n.type === 'report' || n.type ===
                    'warning');
                else if (currentFilter === 'critical') filtered = filtered.filter(n => n.type === 'critical' || n
                    .type === 'error');

                if (currentSearch) {
                    const q = currentSearch.toLowerCase();
                    filtered = filtered.filter(n => getTitle(n).toLowerCase().includes(q) || getMessage(n).toLowerCase()
                        .includes(q));
                }

                renderFeed(filtered);
            }

            async function fetchNotifications() {
                showSkeleton();
                try {
                    const res = await fetch(API_LATEST, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await res.json();
                    if (data.success && Array.isArray(data.notifications)) {
                        allNotifications = data.notifications;
                        updateBadge();
                        updateStats();
                        filterNotifications();
                    } else {
                        showError();
                    }
                } catch (e) {
                    showError();
                }
            }

            async function markAllRead() {
                if (markAllBtn.disabled) return;
                markAllBtn.disabled = true;
                try {
                    const res = await fetch(API_MARK_ALL, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': CSRF,
                            'Content-Type': 'application/json'
                        }
                    });
                    if (res.ok) {
                        allNotifications.forEach(n => {
                            n.read_at = n.read_at || new Date().toISOString();
                        });
                        updateBadge();
                        updateStats();
                        filterNotifications();
                    }
                } catch (e) {}
                markAllBtn.disabled = false;
            }

            searchEl.addEventListener('input', function(e) {
                currentSearch = e.target.value;
                filterNotifications();
            });

            refreshBtn.addEventListener('click', fetchNotifications);
            markAllBtn.addEventListener('click', markAllRead);

            filterControl.addEventListener('click', function(e) {
                const pill = e.target.closest('.filter-pill');
                if (!pill) return;
                filterControl.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
                pill.classList.add('active');
                currentFilter = pill.dataset.filter;
                filterNotifications();
            });

            fetchNotifications();
        })();
    </script>
@endpush
