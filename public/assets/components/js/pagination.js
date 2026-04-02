/**
 * Pagination Component
 * Fungsi: Smooth scroll ke atas saat mengklik link pagination
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Pagination JS loaded'); // Untuk debug
    
    // Select semua link pagination
    const paginationLinks = document.querySelectorAll('.pagination .page-link');
    
    console.log('Found pagination links:', paginationLinks.length); // Untuk debug
    
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const pageItem = this.closest('.page-item');
            
            // Cek apakah link aktif atau disabled
            if (pageItem && 
                !pageItem.classList.contains('disabled') && 
                !pageItem.classList.contains('active')) {
                
                // Smooth scroll ke atas
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                
                // Optional: Tampilkan loading indicator
                document.body.style.cursor = 'wait';
            }
        });
    });
    
    // Reset cursor setelah navigasi selesai
    window.addEventListener('load', function() {
        document.body.style.cursor = 'default';
    });
});