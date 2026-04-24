<script>
    (function () {
        // Restore sidebar collapsed state immediately (before DOMContentLoaded)
        if (window.innerWidth >= 1200) {
            var isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                document.body.classList.add('sidebar-collapsed');
            }
        }

        // Simple loader for async CSS
        var styleLoaded = localStorage.getItem('admin-styles-loaded');
        if (!styleLoaded) {
            var stylesToLoad = [
                "{{ asset('assets/admin/css/styles.min.css') }}",
                "{{ asset('assets/css/icons/tabler-icons/tabler-icons.css') }}",
                "{{ asset('assets/admin/css/admin.css') }}",
                "{{ asset('assets/components/css/modals.css') }}"
            ];

            stylesToLoad.forEach(function (href) {
                var link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = href;
                document.head.appendChild(link);
            });

            localStorage.setItem('admin-styles-loaded', 'true');
        }
    })();
</script>