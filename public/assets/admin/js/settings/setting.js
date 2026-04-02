/**
 * Settings Manager
 * Handles all settings operations with Laravel method spoofing support
 */
document.addEventListener('DOMContentLoaded', function () {
    const SettingsManager = {
        init() {
            this.cacheElements();
            this.bindEvents();
            this.initToasts();
        },

        cacheElements() {
            this.successToast = document.getElementById('successToast');
            this.errorToast = document.getElementById('errorToast');
            this.forms = document.querySelectorAll('.setting-form');
            this.checkboxes = document.querySelectorAll('.setting-checkbox');
            this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        },

        bindEvents() {
            this.bindCheckboxLabels();
            this.bindFormSubmissions();
            this.bindResetButtons();
        },

        initToasts() {
            // Support both Bootstrap 5 and custom toast implementations
            if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                this.successToastInstance = this.successToast ? new bootstrap.Toast(this.successToast) : null;
                this.errorToastInstance = this.errorToast ? new bootstrap.Toast(this.errorToast) : null;
            }
        },

        bindCheckboxLabels() {
            this.checkboxes.forEach(checkbox => {
                const updateLabel = () => {
                    const label = checkbox.nextElementSibling;
                    if (label && label.classList.contains('form-check-label')) {
                        label.textContent = checkbox.checked ? 'Enabled' : 'Disabled';
                    }
                };

                checkbox.addEventListener('change', updateLabel);
                updateLabel(); // Initial state
            });
        },

        bindFormSubmissions() {
            this.forms.forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const group = form.dataset.group;
                    if (group) {
                        this.saveGroupSettings(group, form);
                    }
                });
            });
        },

        bindResetButtons() {
            document.querySelectorAll('.btn-reset').forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const key = button.dataset.key;
                    if (key && confirm('Are you sure you want to reset this setting to default?')) {
                        this.resetSetting(key);
                    }
                });
            });
        },

        /**
         * Save group settings using Laravel method spoofing
         * Laravel requires POST with _method=PUT for PUT requests via form data
         */
        async saveGroupSettings(group, form) {
            const formData = new FormData(form);
            
            // Laravel method spoofing: PUT via POST
            formData.append('_method', 'PUT');
            
            // Process data for proper type casting
            const processedData = this.processFormData(formData);

            try {
                this.setLoadingState(form, true);

                const response = await fetch(`/admin/settings/group/${group}/update`, {
                    method: 'POST', // Laravel method spoofing
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                        // Note: Don't set Content-Type, browser will set it with FormData boundary
                    },
                    body: formData
                });

                const result = await this.handleResponse(response);

                if (result.success) {
                    this.showSuccess(result.message || 'Settings saved successfully!');
                    this.updateFormValues(form, processedData);
                } else {
                    this.showError(result.error || result.message || 'Failed to save settings');
                }
            } catch (error) {
                console.error('Error saving settings:', error);
                this.showError(error.message || 'Error saving settings');
            } finally {
                this.setLoadingState(form, false);
            }
        },

        /**
         * Reset single setting using Laravel method spoofing
         */
        async resetSetting(key) {
            const formData = new FormData();
            formData.append('_method', 'DELETE'); // Laravel method spoofing
            formData.append('_token', this.csrfToken);

            try {
                this.showLoading();

                const response = await fetch(`/admin/settings/item/${key}/reset`, {
                    method: 'POST', // Laravel method spoofing
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const result = await this.handleResponse(response);

                if (result.success) {
                    this.showSuccess(result.message || 'Setting reset to default');
                    
                    // Update input value if provided
                    const input = document.getElementById(key) || 
                                  document.getElementById(`setting-${key.replace(/\./g, '-')}`);
                    if (input && result.value !== undefined) {
                        this.updateInputValue(input, result.value);
                    }

                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    this.showError(result.error || 'Failed to reset setting');
                }
            } catch (error) {
                console.error('Error resetting setting:', error);
                this.showError(error.message || 'Error resetting setting');
            } finally {
                this.hideLoading();
            }
        },

        /**
         * Process form data with proper type casting
         */
        processFormData(formData) {
            const data = {};
            
            for (let [key, value] of formData.entries()) {
                // Skip Laravel internal fields
                if (key === '_token' || key === '_method') continue;

                const input = document.getElementById(key) || 
                              document.querySelector(`[name="${key}"]`);
                
                if (!input) {
                    data[key] = value;
                    continue;
                }

                const type = input.dataset.type || input.type;

                switch (type) {
                    case 'checkbox':
                    case 'boolean':
                    case 'bool':
                        data[key] = input.checked;
                        break;
                    case 'integer':
                    case 'int':
                    case 'number':
                        data[key] = parseInt(value) || 0;
                        break;
                    case 'float':
                    case 'decimal':
                    case 'double':
                        data[key] = parseFloat(value) || 0.0;
                        break;
                    case 'json':
                    case 'array':
                        try {
                            data[key] = JSON.parse(value);
                        } catch {
                            data[key] = value;
                        }
                        break;
                    default:
                        data[key] = value;
                }
            }
            
            return data;
        },

        /**
         * Update form input values after successful save
         */
        updateFormValues(form, data) {
            Object.keys(data).forEach(key => {
                const input = form.querySelector(`[name="${key}"]`);
                if (!input) return;

                this.updateInputValue(input, data[key]);
            });
        },

        /**
         * Update single input value with event dispatch
         */
        updateInputValue(input, value) {
            if (input.type === 'checkbox') {
                input.checked = Boolean(value);
                
                // Update label if exists
                const label = input.nextElementSibling;
                if (label && label.classList.contains('form-check-label')) {
                    label.textContent = input.checked ? 'Enabled' : 'Disabled';
                }
            } else {
                input.value = value;
            }

            // Dispatch change event for any listeners
            input.dispatchEvent(new Event('change', { bubbles: true }));
        },

        /**
         * Handle fetch response with error checking
         */
        async handleResponse(response) {
            if (!response.ok) {
                if (response.status === 403) {
                    throw new Error('Access denied. You do not have permission to perform this action.');
                } else if (response.status === 419) {
                    throw new Error('Session expired. Please refresh the page and try again.');
                } else if (response.status === 422) {
                    const errors = await response.json();
                    throw new Error(this.formatValidationErrors(errors));
                } else {
                    throw new Error(`Server error: ${response.status} ${response.statusText}`);
                }
            }

            return response.json();
        },

        /**
         * Format validation errors from Laravel
         */
        formatValidationErrors(errors) {
            if (errors.message) return errors.message;
            if (errors.errors) {
                return Object.values(errors.errors).flat().join(', ');
            }
            return 'Validation failed';
        },

        /**
         * UI State Management
         */
        setLoadingState(form, isLoading) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = isLoading;
                submitBtn.innerHTML = isLoading 
                    ? `<span class="spinner-border spinner-border-sm me-2"></span>Saving...`
                    : `<i class="bi bi-check-lg me-1"></i>Save Changes`;
            }
        },

        showLoading() {
            const loader = document.getElementById('settingsLoading');
            if (loader) loader.style.display = 'flex';
        },

        hideLoading() {
            const loader = document.getElementById('settingsLoading');
            if (loader) loader.style.display = 'none';
        },

        /**
         * Toast Notifications
         */
        showSuccess(message) {
            if (this.successToastInstance) {
                this.updateToastBody(this.successToast, message, 'success');
                this.successToastInstance.show();
            } else {
                alert(message); // Fallback
            }
        },

        showError(message) {
            if (this.errorToastInstance) {
                this.updateToastBody(this.errorToast, message, 'error');
                this.errorToastInstance.show();
            } else {
                alert('Error: ' + message); // Fallback
            }
        },

        updateToastBody(toastElement, message, type) {
            const toastBody = toastElement.querySelector('.toast-body');
            if (!toastBody) return;

            const icons = {
                success: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2 text-success"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`,
                error: `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2 text-danger"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`
            };

            toastBody.innerHTML = `${icons[type] || ''}${message}`;
        }
    };

    SettingsManager.init();
});

// ============================================================================
// GLOBAL HELPER FUNCTIONS
// ============================================================================

/**
 * Reset single setting (global function for inline onclick)
 * @param {string} key - Setting key to reset
 */
function resetSingleSetting(key) {
    if (!confirm('Are you sure you want to reset this setting to default?')) {
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const loadingEl = document.getElementById('settingsLoading');

    if (loadingEl) loadingEl.style.display = 'flex';

    const formData = new FormData();
    formData.append('_method', 'DELETE'); // Laravel method spoofing
    formData.append('_token', csrfToken);

    fetch(`/admin/settings/item/${key}/reset`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 403) throw new Error('Access denied');
            if (response.status === 419) throw new Error('Session expired');
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (loadingEl) loadingEl.style.display = 'none';

        if (data.success) {
            // Show success message
            if (typeof showSettingsToast === 'function') {
                showSettingsToast(data.message || 'Setting reset successfully!', 'success');
            } else {
                alert(data.message || 'Setting reset successfully!');
            }

            // Update input if exists
            const input = document.getElementById(`setting-${key.replace(/\./g, '-')}`);
            if (input && data.value !== undefined) {
                if (input.type === 'checkbox') {
                    input.checked = Boolean(data.value);
                } else {
                    input.value = data.value;
                }
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }

            setTimeout(() => location.reload(), 1500);
        } else {
            throw new Error(data.error || 'Failed to reset setting');
        }
    })
    .catch(error => {
        if (loadingEl) loadingEl.style.display = 'none';
        
        const message = error.message || 'Network error occurred';
        if (typeof showSettingsToast === 'function') {
            showSettingsToast(message, 'error');
        } else {
            alert('Error: ' + message);
        }
        
        console.error('Reset error:', error);
    });
}

/**
 * Confirm and reset all settings in group
 * @param {string} group - Group name to reset
 */
function confirmResetGroup(group) {
    if (!confirm(`⚠️ Reset all settings in "${group}" group to default values?\n\nThis action cannot be undone.`)) {
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const formData = new FormData();
    formData.append('_method', 'DELETE');
    formData.append('_token', csrfToken);

    fetch(`/admin/settings/group/${group}/reset`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(data.message || 'Settings reset successfully');
            location.reload();
        } else {
            alert(data.error || 'Failed to reset settings');
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
        console.error('Group reset error:', error);
    });
}