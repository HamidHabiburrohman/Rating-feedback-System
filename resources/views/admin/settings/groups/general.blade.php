<form id="form-general" 
      method="POST" 
      action="{{ route('admin.settings.group.update', 'general') }}"
      data-group="general"
      data-reset-route="{{ route('admin.settings.group.reset', 'general') }}">
    @csrf
    @method('PUT')
    
    @include('admin.settings.partials.header.group-header', ['group' => 'general'])
    @include('admin.settings.partials.header.group-description', ['group' => 'general'])
    
    @php
        $subgroups = $settings->groupBy('subgroup')->filter(function($items) {
            return $items->where('is_visible', true)->count() > 0;
        });
    @endphp

    @forelse($subgroups as $subgroup => $subgroupSettings)
        @php
            $visibleSettings = $subgroupSettings->where('is_visible', true);
        @endphp
        
        @if($visibleSettings->count() > 0)
            @include('admin.settings.partials.header.subgroup-header', [
                'subgroup' => $subgroup ?: 'General',
                'count' => $visibleSettings->count()
            ])
            
            <div class="row g-4">
                @foreach($visibleSettings as $setting)
                    @include('admin.settings.partials.forms.setting-card', [
                        'setting' => $setting,
                        'colClass' => 'col-md-6 col-lg-4'
                    ])
                @endforeach
            </div>
        @endif
    @empty
        <div class="alert alert-info">
            No settings found for this group.
        </div>
    @endforelse

    <div class="settings-actions">
        <button type="button" class="btn-reset-group" data-group="general">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                <path d="M3 3v5h5"/>
            </svg>
            Reset to Default
        </button>
        <button type="submit" class="btn-save-group" data-group="general">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                <polyline points="17 21 17 13 7 13 7 21"/>
                <polyline points="7 3 7 8 15 8"/>
            </svg>
            Save Changes
        </button>
    </div>
</form>

@push('admin-scripts')
<script>
(function() {
    'use strict';

    class SettingsManager {
        constructor() {
            this.form = document.getElementById('form-general');
            this.group = this.form?.dataset.group || 'general';
            this.updateUrl = this.form?.action;  
            this.resetUrl = this.form?.dataset.resetRoute;
            this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            if (!this.form || !this.updateUrl) {
                console.error('Form or update URL not found');
                return;
            }
            
            this.init();
        }

        init() {
            this.bindFormSubmit();
            this.bindResetButton();
            this.bindInputEvents();
        }

        bindFormSubmit() {
            this.form.addEventListener('submit', async (e) => {
                e.preventDefault();
                await this.saveSettings();
            });
        }

        bindResetButton() {
            const resetBtn = this.form.querySelector('.btn-reset-group');
            if (resetBtn) {
                resetBtn.addEventListener('click', () => {
                    this.showConfirmDialog(
                        'Reset Settings',
                        'Are you sure you want to reset all general settings to default values?',
                        () => this.resetAll()
                    );
                });
            }
        }

        bindInputEvents() {
            this.form.querySelectorAll('.setting-input').forEach(input => {
                if (input.type === 'checkbox') {
                    const label = input.closest('.card-body')?.querySelector('.form-check-label');
                    if (label) {
                        label.textContent = input.checked ? 'Enabled' : 'Disabled';
                    }
                    
                    input.addEventListener('change', (e) => {
                        const label = e.target.closest('.card-body')?.querySelector('.form-check-label');
                        if (label) {
                            label.textContent = e.target.checked ? 'Enabled' : 'Disabled';
                        }
                    });
                }
            });
        }

        async saveSettings() {
            try {
                this.showLoading();
                
                const formData = new FormData(this.form);
                const data = this.processFormData(formData);
                
                console.log('Saving to URL:', this.updateUrl);  
                
                const response = await fetch(this.updateUrl, {  
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                console.log('Response status:', response.status);  

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const result = await response.json();

                if (result.success) {
                    this.showToast('Settings saved successfully!', 'success');
                    this.updateFormValues(data);
                } else {
                    this.showToast(result.error || 'Failed to save settings', 'error');
                }
            } catch (error) {
                console.error('Save error:', error);
                this.showToast('Error saving settings: ' + error.message, 'error');
            } finally {
                this.hideLoading();
            }
        }

        async resetAll() {
            if (!this.resetUrl) {
                console.error('Reset URL not found');
                return;
            }

            try {
                this.showLoading();
                
                console.log('Resetting at URL:', this.resetUrl); 
                
                const response = await fetch(this.resetUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const result = await response.json();

                if (result.success) {
                    this.showToast('Settings reset to defaults', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    this.showToast(result.error || 'Failed to reset settings', 'error');
                }
            } catch (error) {
                console.error('Reset error:', error);
                this.showToast('Error resetting settings: ' + error.message, 'error');
            } finally {
                this.hideLoading();
            }
        }

        processFormData(formData) {
            const data = {};
            
            for (let [key, value] of formData.entries()) {
                if (key === '_token' || key === '_method') continue;
                
                const input = this.form.querySelector(`[name="${key}"]`);
                const type = input?.dataset.type || input?.type;
                
                if (input?.type === 'checkbox') {
                    data[key] = input.checked ? true : false;
                } else if (type === 'integer' || type === 'int') {
                    data[key] = parseInt(value) || 0;
                } else if (type === 'float' || type === 'decimal') {
                    data[key] = parseFloat(value) || 0;
                } else if (type === 'boolean' || type === 'bool') {
                    data[key] = value === 'true' || value === '1' || value === true;
                } else {
                    data[key] = value;
                }
            }
            
            return data;
        }

        updateFormValues(data) {
            Object.keys(data).forEach(key => {
                const input = this.form.querySelector(`[name="${key}"]`);
                if (!input) return;
                
                const value = data[key];
                
                if (input.type === 'checkbox') {
                    input.checked = Boolean(value);
                    const label = input.closest('.card-body')?.querySelector('.form-check-label');
                    if (label) label.textContent = input.checked ? 'Enabled' : 'Disabled';
                } else {
                    input.value = value;
                }
            });
        }

        showConfirmDialog(title, message, callback) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: title,
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f8773c',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, reset it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) callback();
                });
            } else {
                if (confirm(message)) callback();
            }
        }

        showToast(message, type = 'success') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    text: message,
                    icon: type,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            } else {
                alert(message);
            }
        }

        showLoading() {
            const btn = this.form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    Saving...
                `;
            }
        }

        hideLoading() {
            const btn = this.form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Save Changes
                `;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        new SettingsManager();
    });

})();
</script>
@endpush

@push('styles')
<style>
.settings-actions {
    position: sticky;
    bottom: 0;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
    padding: 1.25rem 1.5rem;
    border-top: 1px solid #e5e7eb;
    margin-top: 2rem;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    border-radius: 0 0 12px 12px;
}

.btn-save-group {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.8rem;
    background: #f8773c;
    border: none;
    border-radius: 40px;
    color: white;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    cursor: pointer;
}

.btn-save-group:hover {
    background: #e0662c;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(248, 119, 60, 0.25);
}

.btn-reset-group {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.8rem;
    background: white;
    border: 1.5px solid #e2e8f0;
    border-radius: 40px;
    color: #475569;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.2s ease;
    cursor: pointer;
}

.btn-reset-group:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #1e293b;
}

.btn-save-group:disabled,
.btn-reset-group:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.settings-loading {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    backdrop-filter: blur(3px);
}
</style>
@endpush