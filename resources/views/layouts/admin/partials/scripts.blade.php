<script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>

<script>
    function getAuthToken() {
        const token = localStorage.getItem('token');
        if (!token) {
            console.warn('No token found in localStorage');
            window.location.href = '/login';
        }
        return token;
    }

    function loadPageContent(page) {
        const contentArea = document.getElementById('spa-content');
        const token = getAuthToken();

        contentArea.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading ${page}...</p>
            </div>
        `;

        fetch(`/api/admin/${page}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.status === 401) {
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                throw new Error('Session expired. Please login again.');
            }
            if (!response.ok) {
                throw new Error(`Server error: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            contentArea.innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">${page.charAt(0).toUpperCase() + page.slice(1)} Management</h4>
                        <div class="mt-4">
                            <pre class="bg-light p-3 rounded">${JSON.stringify(data, null, 2)}</pre>
                        </div>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            contentArea.innerHTML = `
                <div class="alert alert-danger">
                    <h5>Error Loading ${page}</h5>
                    <p>${error.message}</p>
                    ${error.message.includes('Session expired') ? 
                        '<a href="/login" class="btn btn-primary mt-2">Login Again</a>' : 
                        '<button onclick="loadPageContent(\'' + page + '\')" class="btn btn-primary mt-2">Retry</button>'}
                </div>
            `;
            console.error('Load error:', error);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const currentPage = '{{ $page ?? "dashboard" }}';
        
        if (currentPage !== 'dashboard' && !document.querySelector('#spa-content').innerHTML.trim()) {
            loadPageContent(currentPage);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
    // Logout Modal
    const logoutModalElement = document.getElementById('logoutModal');
    if (!logoutModalElement) return;
    
    const logoutModal = new bootstrap.Modal(logoutModalElement);
    const logoutBtn = document.getElementById('logoutBtn');
    const confirmLogoutBtn = document.getElementById('confirmLogout');
    const logoutSpinner = document.getElementById('logoutSpinner');
    const logoutForm = document.getElementById('logoutForm');
    
    // Open logout modal
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            logoutModal.show();
        });
    }
    
    // Handle logout form submission
    if (logoutForm) {
        logoutForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            
            // Show loading spinner
            if (submitBtn) {
                submitBtn.disabled = true;
                if (logoutSpinner) {
                    logoutSpinner.classList.remove('d-none');
                }
            }
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            const openDropdowns = document.querySelectorAll('.dropdown.show');
            openDropdowns.forEach(dropdown => {
                const toggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
                if (toggle) {
                    bootstrap.Dropdown.getInstance(toggle)?.hide();
                }
            });
        }
    });
});
</script>