<script defer src="{{ asset('assets/admin/js/sidebarmenu.js') }}"></script>
<script defer src="{{ asset('assets/admin/js/app.min.js') }}"></script>
<script defer src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>
<script defer src="{{ asset('assets/components/components.js') }}"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{-- Inline JS ini harus tetep jalan, gak perlu diubah --}}
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
                sidebarOverlay.classList.toggle('show');
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
                sidebarOverlay.classList.remove('show');
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
    });

    function getAuthToken() {
        return localStorage.getItem('token');
    }
</script>