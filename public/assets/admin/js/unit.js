document.addEventListener('DOMContentLoaded', function() {
    initializeTomSelect();
    initializeCodeGeneration();
    initializeTimeValidation();
    initializeFormValidation();
    initializeAlerts();
    initializeFilterDropdown();
    initializeSortDropdown();
    initializeSearch();
    initializeSmoothScroll();
});

let selectedStatus = [];
let selectedTypes = [];

function initializeFilterDropdown() {
    const params = new URLSearchParams(window.location.search);
    
    function initState() {
        const status = params.get('status');
        if (status) {
            selectedStatus = status.split(',');
        }
        
        const type = params.get('type');
        if (type) {
            selectedTypes = type.split(',');
        }
        
        updateFilterStyles();
    }
    
    function updateFilterStyles() {
        const activeStyle = 'background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; color: white;';
        const inactiveStyle = 'background: white; border: 1px solid #d1d5db; color: #6b7280;';
        
        document.querySelectorAll('.filter-status').forEach(btn => {
            const value = btn.dataset.value;
            if (value === '') {
                btn.style.cssText = selectedStatus.length ? inactiveStyle : activeStyle;
            } else {
                btn.style.cssText = selectedStatus.includes(value) ? activeStyle : inactiveStyle;
            }
        });
        
        document.querySelectorAll('.filter-type').forEach(btn => {
            const value = btn.dataset.value;
            if (value === '') {
                btn.style.cssText = selectedTypes.length ? inactiveStyle : activeStyle;
            } else {
                btn.style.cssText = selectedTypes.includes(value) ? activeStyle : inactiveStyle;
            }
        });
    }
    
    function bindFilterEvents() {
        const dropdownMenu = document.querySelector('.dropdown-menu');
        if (dropdownMenu) {
            dropdownMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
        
        document.querySelectorAll('.filter-status').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const value = this.dataset.value;
                
                if (value === '') {
                    selectedStatus = [];
                } else {
                    const index = selectedStatus.indexOf(value);
                    if (index === -1) {
                        selectedStatus.push(value);
                    } else {
                        selectedStatus.splice(index, 1);
                    }
                }
                
                updateFilterStyles();
            });
        });
        
        document.querySelectorAll('.filter-type').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const value = this.dataset.value;
                
                if (value === '') {
                    selectedTypes = [];
                } else {
                    const index = selectedTypes.indexOf(value);
                    if (index === -1) {
                        selectedTypes.push(value);
                    } else {
                        selectedTypes.splice(index, 1);
                    }
                }
                
                updateFilterStyles();
            });
        });
    }
    
    function bindApplyFilter() {
        const applyBtn = document.getElementById('applyFilter');
        if (applyBtn) {
            applyBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const url = new URL(window.location.href);
                
                if (selectedTypes.length) {
                    url.searchParams.set('type', selectedTypes.join(','));
                } else {
                    url.searchParams.delete('type');
                }
                
                if (selectedStatus.length) {
                    url.searchParams.set('status', selectedStatus.join(','));
                } else {
                    url.searchParams.delete('status');
                }
                
                url.searchParams.set('page', 1);
                window.location.href = url.toString();
            });
        }
    }
    
    function bindResetFilter() {
        const resetBtn = document.getElementById('resetFilter');
        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                window.location.href = window.location.pathname;
            });
        }
    }
    
    const filterDropdown = document.getElementById('filterDropdown');
    if (filterDropdown) {
        filterDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    initState();
    bindFilterEvents();
    bindApplyFilter();
    bindResetFilter();
}

function initializeSortDropdown() {
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
    
    document.querySelectorAll('#sortDropdown .dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
        });
    });
}

function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;
    
    let timeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            const url = new URL(window.location.href);
            if (this.value.trim()) {
                url.searchParams.set('search', this.value.trim());
            } else {
                url.searchParams.delete('search');
            }
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }, 500);
    });
}

function initializeTomSelect() {
    const facilitiesSelect = document.getElementById('facilities');
    if (!facilitiesSelect) return;
    
    if (typeof TomSelect !== 'undefined') {
        new TomSelect(facilitiesSelect, {
            plugins: ['remove_button'],
            maxItems: null,
            hideSelected: true,
            create: false,
            render: {
                no_results: function() {
                    return '<div class="no-results">Tidak ada fasilitas yang cocok</div>';
                }
            }
        });
    }
}

function initializeCodeGeneration() {
    const nameInput = document.getElementById('name');
    const typeSelect = document.getElementById('unit_type_id');
    const codeInput = document.getElementById('code');
    const regenerateBtn = document.getElementById('regenerateCode');
    
    if (!nameInput || !codeInput) return;
    
    const generateCode = () => {
        const name = nameInput.value.trim();
        const typeOption = typeSelect ? typeSelect.options[typeSelect.selectedIndex] : null;
        
        if (!name || (typeSelect && !typeSelect.value)) {
            codeInput.value = '';
            return;
        }
        
        const typePrefix = typeOption && typeOption.dataset.code 
            ? typeOption.dataset.code 
            : (typeOption ? typeOption.text.substring(0, 3).toUpperCase() : 'UNT');
        
        const words = name.split(/\s+/);
        let nameCode = '';
        
        if (words.length === 1) {
            nameCode = words[0].substring(0, 3).toUpperCase();
        } else {
            nameCode = words.map(w => w.charAt(0).toUpperCase()).join('').substring(0, 3);
        }
        
        const randomNum = Math.floor(Math.random() * 90 + 10);
        codeInput.value = `${typePrefix}-${nameCode}-${randomNum}`;
    };
    
    nameInput.addEventListener('blur', generateCode);
    
    if (typeSelect) {
        typeSelect.addEventListener('change', () => {
            if (nameInput.value.trim()) generateCode();
        });
    }
    
    if (regenerateBtn) {
        regenerateBtn.addEventListener('click', generateCode);
    }
}

function initializeTimeValidation() {
    const openTime = document.getElementById('open_time');
    const closeTime = document.getElementById('close_time');
    const timeValidation = document.getElementById('timeValidation');
    
    if (!openTime || !closeTime) return;
    
    const validateTime = () => {
        const open = openTime.value;
        const close = closeTime.value;
        
        if (!open || !close) {
            if (timeValidation) timeValidation.textContent = '';
            return true;
        }
        
        if (open >= close) {
            if (timeValidation) {
                timeValidation.textContent = 'Jam tutup harus setelah jam buka';
                timeValidation.style.color = 'var(--color-error)';
            }
            closeTime.classList.add('is-invalid');
            return false;
        }
        
        const openDate = new Date(`2000-01-01T${open}`);
        const closeDate = new Date(`2000-01-01T${close}`);
        const duration = (closeDate - openDate) / (1000 * 60 * 60);
        
        if (timeValidation) {
            timeValidation.textContent = `Durasi: ${duration} jam`;
            timeValidation.style.color = 'var(--color-success)';
        }
        closeTime.classList.remove('is-invalid');
        return true;
    };
    
    openTime.addEventListener('change', validateTime);
    closeTime.addEventListener('change', validateTime);
}

function initializeFormValidation() {
    const form = document.getElementById('unitForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        const openTime = document.getElementById('open_time');
        const closeTime = document.getElementById('close_time');
        
        if (openTime && closeTime) {
            const open = openTime.value;
            const close = closeTime.value;
            
            if (open && close && open >= close) {
                e.preventDefault();
                closeTime.classList.add('is-invalid');
                closeTime.focus();
                return;
            }
        }
        
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        let firstError = null;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
                if (!firstError) firstError = field;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
            return;
        }
        
        if (submitBtn) {
            submitBtn.disabled = true;
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoader = submitBtn.querySelector('.btn-loader');
            if (btnText) btnText.hidden = true;
            if (btnLoader) btnLoader.hidden = false;
        }
    });
    
    const inputs = form.querySelectorAll('.form-input, .form-select, .form-textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
}

function initializeAlerts() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.classList.contains('show')) {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                if (bsAlert) bsAlert.close();
            }
        }, 5000);
    });
}

function initializeSmoothScroll() {
    document.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!this.parentElement.classList.contains('disabled') && 
                !this.parentElement.classList.contains('active')) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const alertClose = document.querySelector('.alert-close');
    const alertBox = document.querySelector('.alert-success');
    
    if (alertClose && alertBox) {
        alertClose.addEventListener('click', function() {
            alertBox.style.opacity = '0';
            alertBox.style.transform = 'translateY(-10px)';
            alertBox.style.transition = 'all 0.3s ease';
            
            setTimeout(() => {
                alertBox.remove();
            }, 300);
        });
        
        setTimeout(() => {
            if (alertBox.parentNode) {
                alertClose.click();
            }
        }, 5000);
    }
    
    const ratingBars = document.querySelectorAll('.rating-fill');
    
    if (ratingBars.length > 0) {
        const animateBars = () => {
            ratingBars.forEach(bar => {
                const rect = bar.getBoundingClientRect();
                const isVisible = rect.top < window.innerHeight && rect.bottom >= 0;
                
                if (isVisible) {
                    const width = bar.style.width;
                    bar.style.width = '0';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 100);
                }
            });
        };
        
        setTimeout(animateBars, 300);
        
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    animateBars();
                    ticking = false;
                });
                ticking = true;
            }
        });
    }
    
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    const galleryItems = document.querySelectorAll('.gallery-item img');
    
    galleryItems.forEach(img => {
        img.style.cursor = 'pointer';
        img.addEventListener('click', function() {
            const modal = document.createElement('div');
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.9);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                cursor: pointer;
                opacity: 0;
                transition: opacity 0.3s ease;
            `;
            
            const modalImg = document.createElement('img');
            modalImg.src = this.src.replace('thumbnail', 'photo');
            modalImg.style.cssText = `
                max-width: 90%;
                max-height: 90%;
                object-fit: contain;
                border-radius: 8px;
            `;
            
            modal.appendChild(modalImg);
            document.body.appendChild(modal);
            
            requestAnimationFrame(() => {
                modal.style.opacity = '1';
            });
            
            modal.addEventListener('click', function() {
                this.style.opacity = '0';
                setTimeout(() => this.remove(), 300);
            });
        });
    });
});