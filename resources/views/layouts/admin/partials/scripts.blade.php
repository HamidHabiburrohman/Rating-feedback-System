<script defer src="{{ asset('assets/admin/js/sidebarmenu.js') }}"></script>
<script defer src="{{ asset('assets/admin/js/app.min.js') }}"></script>
<script defer src="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.min.js"></script>
<script defer src="{{ asset('assets/components/components.js') }}"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const leftSidebar = document.querySelector('.left-sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            if (window.innerWidth < 1200) {
                leftSidebar.classList.toggle('show');
                if (sidebarOverlay) sidebarOverlay.classList.toggle('show');
            } else {
                const currentType = document.body.getAttribute('data-sidebartype');
                const newType = currentType === 'mini' ? 'full' : 'mini';
                document.body.setAttribute('data-sidebartype', newType);
                localStorage.setItem('sidebarType', newType);
            }
        }

        function closeSidebar() {
            leftSidebar.classList.remove('show');
            if (sidebarOverlay) sidebarOverlay.classList.remove('show');
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }

        if (sidebarClose) {
            sidebarClose.addEventListener('click', closeSidebar);
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }

        const savedSidebarType = localStorage.getItem('sidebarType');
        if (savedSidebarType && window.innerWidth >= 1200) {
            document.body.setAttribute('data-sidebartype', savedSidebarType);
        } else if (window.innerWidth >= 1200) {
            document.body.setAttribute('data-sidebartype', 'full');
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
</script>
