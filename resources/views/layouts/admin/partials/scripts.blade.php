<script defer src="{{ asset('assets/admin/js/sidebarmenu.js') }}"></script>
<script defer src="{{ asset('assets/admin/js/app.min.js') }}"></script>
<script defer src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>
<script defer src="{{ asset('assets/components/components.js') }}"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const elements = [
            document.body,
            document.querySelector('.page-wrapper'),
            document.querySelector('.body-wrapper'),
            document.querySelector('.body-wrapper-inner'),
            document.querySelector('.container-fluid'),
            document.getElementById('main-content')
        ];

        elements.forEach(el => {
            if (el) {
                el.style.backgroundColor = '#ffffff';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const leftSidebar = document.querySelector('.left-sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            if (window.innerWidth < 1200) {
                leftSidebar.classList.toggle('show');
                if (sidebarOverlay) sidebarOverlay.classList.toggle('show');
            } else {
                document.body.classList.toggle('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', document.body.classList.contains('sidebar-collapsed'));
            }
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }

        if (sidebarClose) {
            sidebarClose.addEventListener('click', function () {
                leftSidebar.classList.remove('show');
                if (sidebarOverlay) sidebarOverlay.classList.remove('show');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function () {
                leftSidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }

        if (window.innerWidth >= 1200) {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                document.body.classList.add('sidebar-collapsed');
            }
        }

        const searchModal = document.getElementById('searchModal');
        const globalSearch = document.getElementById('globalSearch');
        const searchClose = document.querySelector('.search-close');
        const searchBackdrop = document.querySelector('.search-backdrop');

        if (globalSearch) {
            globalSearch.addEventListener('focus', function() {
                if (searchModal) {
                    searchModal.classList.add('show');
                    document.body.classList.add('search-modal-open');
                }
            });
        }

        if (searchClose) {
            searchClose.addEventListener('click', function() {
                if (searchModal) {
                    searchModal.classList.remove('show');
                    document.body.classList.remove('search-modal-open');
                }
            });
        }

        if (searchBackdrop) {
            searchBackdrop.addEventListener('click', function() {
                if (searchModal) {
                    searchModal.classList.remove('show');
                    document.body.classList.remove('search-modal-open');
                }
            });
        }

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                if (searchModal) {
                    searchModal.classList.toggle('show');
                    document.body.classList.toggle('search-modal-open');
                    if (searchModal.classList.contains('show')) {
                        const searchInput = document.getElementById('searchInput');
                        if (searchInput) setTimeout(() => searchInput.focus(), 100);
                    }
                }
            }
            if (e.key === 'Escape' && searchModal && searchModal.classList.contains('show')) {
                searchModal.classList.remove('show');
                document.body.classList.remove('search-modal-open');
            }
        });
    });

    function getAuthToken() {
        return localStorage.getItem('token');
    }
</script>