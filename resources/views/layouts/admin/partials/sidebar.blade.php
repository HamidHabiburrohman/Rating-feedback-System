<aside class="left-sidebar settings-sidebar" id="leftSidebar">
    <div class="sidebar-container">
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.dashboard') }}" class="text-nowrap logo-img">
                <svg class="logo-full" xmlns="http://www.w3.org/2000/svg" width="188" height="50" fill="none" viewBox="0 0 188 50">
                    <path d="M19 8.711L1.615 18.165a1 1 0 0 0 0 1.757l16.907 9.195a1 1 0 0 0 .956 0l13.59-7.392a1 1 0 0 1 1.478.878v9.218a1 1 0 0 0 1 1H37a1 1 0 0 0 1-1V19.044" fill="#2f2d2d"/>
                    <path d="M8.387 28.626a1 1 0 0 0-1.478.878v4.612a1 1 0 0 0 .522.878l11.091 6.035a1 1 0 0 0 .956 0l11.091-6.035a1 1 0 0 0 .522-.878v-4.612a1 1 0 0 0-1.478-.878l-10.135 5.515a1 1 0 0 1-.956 0L8.387 28.626z" fill="#ff590d"/>
                    <path d="M57.848 35V17.12h2.832V35h-2.832zm11.891.144c-1.36 0-2.416-.384-3.168-1.152s-1.128-1.848-1.128-3.24v-6.288h-2.28v-2.448h.36c.608 0 1.08-.176 1.416-.528s.504-.832.504-1.44V19.04h2.712v2.976h2.952v2.448h-2.952v6.168c0 .448.072.832.216 1.152a1.59 1.59 0 0 0 .696.72c.32.16.736.24 1.248.24.128 0 .272-.008.432-.024l.456-.048V35l-.744.096a6.25 6.25 0 0 1-.72.048zm 9.972.144c-1.296 0-2.448-.296-3.456-.888-.992-.608-1.768-1.424-2.328-2.448-.56-1.04-.84-2.2-.84-3.48 0-1.312.28-2.472.84-3.48.576-1.008 1.344-1.8 2.304-2.376.96-.592 2.048-.888 3.264-.888.976 0 1.848.168 2.616.504s1.416.8 1.944 1.392a5.87 5.87 0 0 1 1.2 1.992 6.65 6.65 0 0 1 .432 2.4l-.024.648c-.016.224-.048.432-.096.624H75.223v-2.16h8.808l-1.296.984c.16-.78 4.104-1.48-.168-2.088-.256-.624-.656-1.112-1.2-1.464-.528-.368-1.152-.552-1.872-.552s-1.36.184-1.92.552c-.56.352-.992.864-1.296 1.536-.304.656-.424 1.456-.36 2.4-.08.88.04 1.648.36 2.304a3.81 3.81 0 0 0 1.392 1.536c.608.368 1.296.552 2.064.552.784 0 1.448-.176 1.992-.528.56-.352 1-.808 1.32-1.368l2.208 1.08c-.256.608-.656 1.16-1.2 1.656-.528.48-1.168.864-1.92 1.152.736.272-1.544.408-2.424.408zM88.334 35V22.016h2.592v2.544l-.312-.336c.32-.816.832-1.432 1.536-1.848.704-.432 1.52-.648 2.448-.648.96 0 1.808.208 2.544.624s1.312.992 1.728 1.728.624 1.584.624 2.544V35h-2.688v-7.656c0-.656-.12-1.208-.36-1.656-.24-.464-.584-.816-1.032-1.056-.432-.256-.928-.384-1.488-.384s-1.064.128-1.512.384c-.432.24-.768.592-1.008 1.056s-.36 1.016-.36 1.656V35h-2.712zm17.951.288c-.88 0-1.656-.152-2.328-.456-.656-.32-1.168-.752-1.536-1.296-.368-.56-.552-1.216-.552-1.968 0-.704.152-1.336.456-1.896.32-.56.808-1.032 1.464-1.416s1.48-.656 2.472-.816l4.512-.744v2.136l-3.984.696c-.72.128-1.248.36-1.584.696-.336.32-.504.736-.504 1.248a1.55 1.55 0 0 0 .552 1.224c.384.304.872.456 1.464.456.736 0 1.376-.16 1.92-.48.56-.32.992-.744 1.296-1.272.304-.544.456-1.144.456-1.8v-3.336c0-.64-.24-1.16-.72-1.56-.464-.416-1.088-.624-1.872-.624-.72 0-1.352.192-1.896.576-.528.368-.92.848-1.176 1.44l-2.256-1.128c.24-.64.632-1.2 1.176-1.68.544-.496 1.176-.88 1.896-1.152a6.66 6.66 0 0 1 2.327-.408c1.025 0 1.929.192 2.713.576.8.384 1.416.92 1.848 1.608.448.672.672 1.456.672 2.352V35h-2.592v-2.352l.552.072c-.304.528-.696.984-1.176 1.368a5.07 5.07 0 0 1-1.608.888c-.592.208-1.256.312-1.992.312zm14.787 0c-1.328 0-2.496-.328-3.504-.984-.992-.656-1.688-1.536-2.089-2.64l2.089-.984c.352.736.832 1.32 1.44 1.752.624.432 1.312.648 2.064.648.64 0 1.16-.144 1.56-.432a1.37 1.37 0 0 0 .6-1.176c0-.32-.088-.576-.264-.768-.176-.208-.4-.376-.672-.504a3.62 3.62 0 0 0-.792-.288l-2.04-.576c-1.12-.32-1.96-.8-2.52-1.44-.544-.656-.816-1.416-.816-2.28 0-.784.2-1.464.6-2.04.4-.592.952-1.048 1.656-1.368s1.496-.48 2.376-.48c1.184 0 2.24.296 3.168.888a4.68 4.68 0 0 1 1.968 2.424l-2.088.984c-.256-.624-.664-1.12-1.224-1.488-.544-.368-1.16-.552-1.848-.552-.592 0-1.064.144-1.416.432-.352.272-.528.632-.528 1.08 0 .304.08.56.24.768a1.91 1.91 0 0 0 .624.48c.256.112.52.208.792.288l2.112.624c1.072.304 1.896.784 2.472 1.44.576.64.864 1.408.864 2.304 0 .768-.208 1.448-.624 2.04-.4.576-.96 1.032-1.68 1.368-.72.32-1.56.48-2.52.48zm18.636 0c-1.328 0-2.512-.28-3.552-.84-1.024-.56-1.832-1.328-2.424-2.304-.576-.976-.864-2.088-.864-3.336V17.12h2.832v11.568c0 .784.168 1.48.504 2.088a3.76 3.76 0 0 0 1.416 1.416c.608.336 1.304.504 2.088.504.8 0 1.496-.168 2.088-.504.608-.336 1.08-.808 1.416-1.416.352-.608.528-1.304.528-2.088V17.12h2.808v11.688c0 1.248-.28 2.36-.864 3.336-.56.976-1.384 1.744-2.424 2.304-1.024.56-2.208.84-3.552.84zM149.834 35V22.016h2.592v2.544l-.312-.336c.32-.816.832-1.432 1.536-1.848.704-.432 1.52-.648 2.448-.648.96 0 1.808.208 2.544.624s1.312.992 1.728 1.728.624 1.584.624 2.544V35h-2.688v-7.656c0-.656-.12-1.208-.36-1.656-.24-.464-.584-.816-1.032-1.056-.432-.256-.928-.384-1.488-.384s-1.064.128-1.512.384c-.432.24-.768.592-1.008 1.056s-.36 1.016-.36 1.656V35h-2.712zm14.062 0V22.016h2.713V35h-2.713zm0-14.76v-3.12h2.713v3.12h-2.713zm11.499 14.904c-1.36 0-2.416-.384-3.168-1.152s-1.128-1.848-1.128-3.24v-6.288h-2.28v-2.448h.36c.608 0 1.08-.176 1.416-.528s.504-.832.504-1.44V19.04h2.712v2.976h2.952v2.448h-2.952v6.168c0 .448.072.832.216 1.152a1.59 1.59 0 0 0 .696.72c.32.16.736.24 1.248.24.128 0 .272-.008.432-.024l.456-.048V35l-.744.096a6.25 6.25 0 0 1-.72.048z" fill="#1b1b1b"/>
                </svg>
                <svg class="logo-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 40 40">
                    <rect width="40" height="40" fill="#fff5f0" rx="8"/>
                    <path d="M20 10.5L8 18.5a0.8 0.8 0 0 0 0 1.4l14.5 7.9a0.8 0.8 0 0 0 0.76 0l11.68-6.36a0.8 0.8 0 0 1 1.18 0.7v7.96a0.8 0.8 0 0 0 0.8 0.8h1.04a0.8 0.8 0 0 0 0.8-0.8v-13" stroke="#ff590d" stroke-width="1.5" fill="none"/>
                    <path d="M12.3 27a0.8 0.8 0 0 0-1.18 0.7v3.76a0.8 0.8 0 0 0 0.416 0.7l9.54 4.9a0.8 0.8 0 0 0 0.76 0l9.54-4.9a0.8 0.8 0 0 0 0.416-0.7v-3.76a0.8 0.8 0 0 0-1.18-0.7l-8.72 4.5a0.8 0.8 0 0 1-0.76 0l-8.72-4.5z" stroke="#2f2d2d" stroke-width="1.5" fill="none"/>
                </svg>
            </a>
            <button class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarClose">
                <i class="ti ti-x fs-8"></i>
            </button>
        </div>

        <nav class="sidebar-nav settings-panel-nav sidebar-scroll">
            <div class="settings-nav">
                <div class="nav-small-cap">
                    <iconify-icon icon="solar:home-smile-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                    <span class="hide-menu">Dashboard</span>
                </div>

                <a class="settings-nav-item {{ request()->routeIs('admin.dashboard*') ? 'settings-nav-active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <span class="settings-nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-width="1.5"
                                d="M3 7.4V3.6a.6.6 0 0 1 .6-.6h5.8a.6.6 0 0 1 .6.6v3.8a.6.6 0 0 1-.6.6H3.6a.6.6 0 0 1-.6-.6Zm11 13v-3.8a.6.6 0 0 1 .6-.6h5.8a.6.6 0 0 1 .6.6v3.8a.6.6 0 0 1-.6.6h-5.8a.6.6 0 0 1-.6-.6Zm0-8V3.6a.6.6 0 0 1 .6-.6h5.8a.6.6 0 0 1 .6.6v8.8a.6.6 0 0 1-.6.6h-5.8a.6.6 0 0 1-.6-.6Zm-11 8v-8.8a.6.6 0 0 1 .6-.6h5.8a.6.6 0 0 1 .6.6v8.8a.6.6 0 0 1-.6.6H3.6a.6.6 0 0 1-.6-.6Z"/>
                        </svg>
                    </span>
                    <span class="settings-nav-label hide-menu">Dashboard</span>
                </a>

                <div class="sidebar-section-divider"></div>

                <div class="nav-small-cap">
                    <iconify-icon icon="solar:database-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                    <span class="hide-menu">Master Data</span>
                </div>

                <a class="settings-nav-item {{ request()->routeIs('admin.unit-types.*') ? 'settings-nav-active' : '' }}"
                    href="{{ route('admin.unit-types.index') }}">
                    <span class="settings-nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" d="M6.5 8h4M14 8h3.5"/>
                                <path stroke-linejoin="round" d="M2 15V9a4 4 0 0 1 4-4h12a4 4 0 0 1 4 4v6a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4Z"/>
                                <path d="M12 12.5a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3Z"/>
                            </g>
                        </svg>
                    </span>
                    <span class="settings-nav-label hide-menu">Unit Types</span>
                </a>

                <a class="settings-nav-item {{ request()->routeIs('admin.unit-departments.*') ? 'settings-nav-active' : '' }}"
                    href="{{ route('admin.unit-departments.index') }}">
                    <span class="settings-nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M2 18.5V12.1c0-1.12 0-1.68.218-2.108.192-.377.497-.682.874-.874C3.52 8.9 4.08 8.9 5.2 8.9h13.6c1.12 0 1.68 0 2.108.218.377.192.682.497.874.874.218.428.218.988.218 2.108v6.4c0 1.12 0 1.68-.218 2.108a2.002 2.002 0 0 1-.874.874c-.428.218-.988.218-2.108.218H5.2c-1.12 0-1.68 0-2.108-.218a2 2 0 0 1-.874-.874C2 20.18 2 19.62 2 18.5Z"/>
                                <path d="M7 8.9V7.5c0-1.12 0-1.68.218-2.108.192-.377.497-.682.874-.874C8.52 4.3 9.08 4.3 10.2 4.3h3.6c1.12 0 1.68 0 2.108.218.377.192.682.497.874.874.218.428.218.988.218 2.108v1.4"/>
                                <path stroke-linecap="round" d="M12 15.5v2"/>
                            </g>
                        </svg>
                    </span>
                    <span class="settings-nav-label hide-menu">Departments</span>
                </a>

                <a class="settings-nav-item {{ request()->routeIs('admin.facilities.*') ? 'settings-nav-active' : '' }}"
                    href="{{ route('admin.facilities.index') }}">
                    <span class="settings-nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M22 21H2" stroke-linecap="round"/>
                                <path stroke-linecap="round" d="M18 21V7m0 0v-3h-4v3m4-3h4"/>
                                <path d="M4 21V9c0-.943 0-1.414.293-1.707C4.586 7 5.057 7 6 7h5c.943 0 1.414 0 1.707.293C13 7.586 13 8.057 13 9v12"/>
                                <path stroke-linecap="round" d="M8 11h2M8 15h2"/>
                            </g>
                        </svg>
                    </span>
                    <span class="settings-nav-label hide-menu">Facilities</span>
                </a>

                <a class="settings-nav-item {{ request()->routeIs('admin.rating-categories.*') ? 'settings-nav-active' : '' }}"
                    href="{{ route('admin.rating-categories.index') }}">
                    <span class="settings-nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-width="1.5"
                                d="M12 2l2.5 5.5 6 .5-4.5 4 1.5 6-5.5-3.5-5.5 3.5 1.5-6-4.5-4 6-.5L12 2z"/>
                        </svg>
                    </span>
                    <span class="settings-nav-label hide-menu">Rating Categories</span>
                </a>

                <a class="settings-nav-item {{ request()->routeIs('admin.report-categories.*') ? 'settings-nav-active' : '' }}"
                    href="{{ route('admin.report-categories.index') }}">
                    <span class="settings-nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 8v4l2 2m6-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                            </g>
                        </svg>
                    </span>
                    <span class="settings-nav-label hide-menu">Report Categories</span>
                </a>

                <div class="sidebar-section-divider"></div>

                <div class="nav-small-cap">
                    <iconify-icon icon="solar:buildings-2-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                    <span class="hide-menu">Unit Management</span>
                </div>

                <a class="settings-nav-item {{ request()->routeIs('admin.units.*') ? 'settings-nav-active' : '' }}"
                    href="{{ route('admin.units.index') }}">
                    <span class="settings-nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M17 11.805c0-.346 0-.519.052-.673c.151-.448.55-.621.95-.803c.448-.205.672-.307.895-.325c.252-.02.505.034.721.155c.286.16.486.466.69.714c.943 1.146 1.415 1.719 1.587 2.35c.14.51.14 1.044 0 1.553c-.251.922-1.046 1.694-1.635 2.41c-.301.365-.452.548-.642.655a1.27 1.27 0 0 1-.721.155c-.223-.018-.447-.12-.896-.325c-.4-.182-.798-.355-.949-.803c-.052-.154-.052-.327-.052-.672zm-10 0c0-.436-.012-.827-.364-1.133c-.128-.111-.298-.188-.637-.343c-.449-.204-.673-.307-.896-.325c-.667-.054-1.026.402-1.41.87c-.944 1.145-1.416 1.718-1.589 2.35a2.94 2.94 0 0 0 0 1.553c.252.921 1.048 1.694 1.636 2.409c.371.45.726.861 1.363.81c.223-.018.447-.12.896-.325c.34-.154.509-.232.637-.343c.352-.306.364-.697.364-1.132z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 10.5V9c0-3.866-3.582-7-8-7S4 5.134 4 9v1.5m16 7c0 4.5-4 4.5-8 4.5"/>
                            </g>
                        </svg>
                    </span>
                    <span class="settings-nav-label hide-menu">Units</span>
                </a>

                <div class="sidebar-section-divider"></div>

                <div class="nav-small-cap">
                    <iconify-icon icon="solar:users-group-rounded-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                    <span class="hide-menu">HR Management</span>
                </div>

                <a class="settings-nav-item {{ request()->routeIs('admin.employees.*') ? 'settings-nav-active' : '' }}"
                    href="{{ route('admin.employees.index') }}">
                    <span class="settings-nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM5.25 9.75a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                            </g>
                        </svg>
                    </span>
                    <span class="settings-nav-label hide-menu">Employees</span>
                </a>

                <div class="sidebar-section-divider"></div>

                <div class="nav-small-cap">
                    <iconify-icon icon="solar:chat-line-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                    <span class="hide-menu">Feedback Management</span>
                </div>

                <a class="settings-nav-item {{ request()->routeIs('admin.ratings.*') ? 'settings-nav-active' : '' }}"
                    href="{{ route('admin.ratings.index') }}">
                    <span class="settings-nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-width="1.5"
                                d="M12 2l2.5 5.5 6 .5-4.5 4 1.5 6-5.5-3.5-5.5 3.5 1.5-6-4.5-4 6-.5L12 2z"/>
                        </svg>
                    </span>
                    <span class="settings-nav-label hide-menu">Ratings</span>
                </a>

                <a class="settings-nav-item {{ request()->routeIs('admin.reports.*') ? 'settings-nav-active' : '' }}"
                    href="{{ route('admin.reports.index') }}">
                    <span class="settings-nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-width="1.5"
                                d="M12 8v4l2 2m6-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                        </svg>
                    </span>
                    <span class="settings-nav-label hide-menu">Reports</span>
                </a>

                <div class="sidebar-section-divider"></div>

                <div class="nav-small-cap">
                    <iconify-icon icon="solar:settings-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                    <span class="hide-menu">System</span>
                </div>

                <a class="settings-nav-item {{ request()->routeIs('admin.moderation-logs.*') ? 'settings-nav-active' : '' }}"
                    href="{{ route('admin.moderation-logs.index') }}">
                    <span class="settings-nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" d="M12 8v4l2 2"/>
                                <path d="M3 10c0-3.771 0-5.657 1.172-6.828C5.343 2 7.229 2 11 2h2c3.771 0 5.657 0 6.828 1.172C21 4.343 21 6.229 21 10v4c0 3.771 0 5.657-1.172 6.828C18.657 22 16.771 22 13 22h-2c-3.771 0-5.657 0-6.828-1.172C3 19.657 3 17.771 3 14v-4z"/>
                                <path stroke-linecap="round" d="M8 2v3M16 2v3"/>
                            </g>
                        </svg>
                    </span>
                    <span class="settings-nav-label hide-menu">Moderation Logs</span>
                </a>

                <a class="settings-nav-item {{ request()->routeIs('admin.exports.*') ? 'settings-nav-active' : '' }}"
                    href="{{ route('admin.exports.index') }}">
                    <span class="settings-nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M4 16v1.5c0 1.4 0 2.1.272 2.635a2.5 2.5 0 0 0 1.093 1.092C5.9 21.5 6.6 21.5 8 21.5h8c1.4 0 2.1 0 2.635-.273a2.5 2.5 0 0 0 1.092-1.092c.273-.535.273-1.235.273-2.635V16"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-3-3m3 3l3-3"/>
                            </g>
                        </svg>
                    </span>
                    <span class="settings-nav-label hide-menu">Export Data</span>
                </a>

                <a class="settings-nav-item {{ request()->routeIs('admin.settings.*') ? 'settings-nav-active' : '' }}"
                    href="{{ route('admin.settings.index') }}">
                    <span class="settings-nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 15a3 3 0 100-6 3 3 0 000 6z"/>
                                <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/>
                            </g>
                        </svg>
                    </span>
                    <span class="settings-nav-label hide-menu">Settings</span>
                </a>

                <a class="settings-nav-item {{ request()->routeIs('admin.notifications.*') ? 'settings-nav-active' : '' }}"
                    href="{{ route('admin.notifications.index') }}">
                    <span class="settings-nav-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </g>
                        </svg>
                    </span>
                    <span class="settings-nav-label hide-menu">Notifications</span>
                </a>
            </div>
        </nav>
    </div>

    <style>
        .logo-full {
            display: block;
            width: 100%;
            max-width: 160px;
        }

        .logo-icon {
            display: none;
            width: 32px;
            height: 32px;
        }

        body[data-sidebartype="mini"] .logo-full {
            display: none;
        }

        body[data-sidebartype="mini"] .logo-icon {
            display: block;
        }
    </style>
</aside>