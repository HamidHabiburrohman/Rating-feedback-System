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