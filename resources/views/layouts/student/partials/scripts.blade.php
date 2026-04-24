<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="{{  asset('assets/student/js/Student.js') }}"></script>

<script>
    // Optional: global JS untuk dropdown, search, dll.
    document.addEventListener('DOMContentLoaded', function () {
        // Contoh: toggle dropdown manual
        const moreBtn = document.querySelectorAll('[data-dropdown-btn]');
        moreBtn.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const target = document.querySelector(btn.dataset.dropdownTarget);
                if (target) target.classList.toggle('hidden');
            });
        });
    });
</script>