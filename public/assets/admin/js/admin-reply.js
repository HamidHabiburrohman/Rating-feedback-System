document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const searchInput = document.getElementById('searchInput');
    
    function bindSearch() {
        if (searchInput) {
            let timeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    const url = new URL(window.location.href);
                    if (this.value) {
                        url.searchParams.set('search', this.value);
                    } else {
                        url.searchParams.delete('search');
                    }
                    url.searchParams.set('page', 1);
                    window.location.href = url;
                }, 500);
            });
        }
    }
    
    function bindDropdownToggles() {
        document.querySelectorAll('.dropdown-toggle-btn').forEach(btn => {
            const icon = btn.querySelector('.dropdown-icon');
            if (!icon) return;
            
            btn.addEventListener('show.bs.dropdown', () => {
                icon.style.transform = 'rotate(180deg)';
            });
            
            btn.addEventListener('hide.bs.dropdown', () => {
                icon.style.transform = 'rotate(0)';
            });
        });
    }
    
    function initViewReplyModal() {
        window.viewReply = function(id, message, admin, date) {
            const modalBody = document.querySelector('#viewReplyModal .modal-body');
            if (!modalBody) return;
            
            modalBody.innerHTML = `
                <div class="mb-3">
                    <label class="text-muted small mb-1">Admin</label>
                    <div class="fw-semibold">${admin}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small mb-1">Date</label>
                    <div>${date}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small mb-1">Reply Message</label>
                    <div class="p-3 bg-light rounded-3">${message.replace(/\n/g, '<br>')}</div>
                </div>
            `;
            
            const modal = document.getElementById('viewReplyModal');
            if (modal) {
                new bootstrap.Modal(modal).show();
            }
        };
    }
    
    function initEditReplyModal() {
        window.editReply = function(id, message) {
            const messageInput = document.getElementById('editReplyMessage');
            const form = document.getElementById('editReplyForm');
            
            if (messageInput) messageInput.value = message;
            if (form) form.action = `/admin/admin-replies/${id}`;
            
            const modal = document.getElementById('editReplyModal');
            if (modal) {
                new bootstrap.Modal(modal).show();
            }
        };
    }
    
    function initAutoDismissAlerts() {
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                if (alert.classList.contains('show')) {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    }
    
    function initSmoothScroll() {
        document.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!this.parentElement.classList.contains('disabled') && 
                    !this.parentElement.classList.contains('active')) {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        });
    }
    
    bindSearch();
    bindDropdownToggles();
    initViewReplyModal();
    initEditReplyModal();
    initAutoDismissAlerts();
    initSmoothScroll();
});