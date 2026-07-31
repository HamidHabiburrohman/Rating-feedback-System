document.addEventListener('DOMContentLoaded', function() {
    initializeTomSelect();
    initializeCodeGeneration();
    initializeSlugGeneration();
    initializeTimeValidation();
    initializeFormValidation();
    initializeAlerts();
});

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

function initializeSlugGeneration() {
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    
    if (!nameInput || !slugInput) return;
    
    let slugEdited = false;
    
    slugInput.addEventListener('input', function() {
        slugEdited = true;
    });
    
    nameInput.addEventListener('input', function() {
        if (!slugEdited && (!slugInput.value || slugInput.value === '')) {
            slugInput.value = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-|-$/g, '');
        }
    });
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
    const form = document.querySelector('form[id$="Form"]');
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