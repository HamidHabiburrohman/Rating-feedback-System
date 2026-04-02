const openMenu = document.getElementById('openMenu');
const closeMenu = document.getElementById('closeMenu');
const mobileMenu = document.getElementById('mobileMenu');

openMenu.addEventListener('click', () => mobileMenu.classList.remove('translate-x-full'));
closeMenu.addEventListener('click', () => mobileMenu.classList.add('translate-x-full'));

mobileMenu.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => mobileMenu.classList.add('translate-x-full'));
});