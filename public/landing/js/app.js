document.addEventListener('DOMContentLoaded', function() {
    const openMenu = document.getElementById('openMenu');
    const closeMenu = document.getElementById('closeMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    
    // Cek apakah semua element ada sebelum pakai addEventListener
    if (openMenu && closeMenu && mobileMenu) {
        openMenu.addEventListener('click', () => mobileMenu.classList.remove('translate-x-full'));
        closeMenu.addEventListener('click', () => mobileMenu.classList.add('translate-x-full'));
        
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => mobileMenu.classList.add('translate-x-full'));
        });
    } else {
        console.warn('Mobile menu elements not found on this page');
        console.log('Missing:', {
            openMenu: !!openMenu,
            closeMenu: !!closeMenu,
            mobileMenu: !!mobileMenu
        });
    }
});