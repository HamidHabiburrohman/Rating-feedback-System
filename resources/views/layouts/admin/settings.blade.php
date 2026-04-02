@extends('layouts.admin.app')

@section('title', 'Settings')

@section('admin-content')
    <div class="settings-wrapper">
        <div class="settings-header">
            <h1 class="h3 mb-0">System Settings</h1>
            <p class="text-muted">Configure and manage all system settings</p>
        </div>

        <div class="settings-content">
            @yield('settings-content')
        </div>
    </div>
@endsection

@push('admin-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Hide loading overlay
            setTimeout(() => {
                document.getElementById('settingsLoading').style.display = 'none';
            }, 500);

            // Initialize toasts
            const successToast = new bootstrap.Toast(document.getElementById('settingsSuccessToast'));
            const errorToast = new bootstrap.Toast(document.getElementById('settingsErrorToast'));

            // CSRF setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Refresh cache button
            document.getElementById('refreshCacheBtn')?.addEventListener('click', function (e) {
                e.preventDefault();

                document.getElementById('settingsLoading').style.display = 'flex';

                fetch('{{ route("admin.settings.refresh.cache") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('settingsLoading').style.display = 'none';

                        if (data.success) {
                            showSettingsToast('Cache refreshed successfully!', 'success');
                        } else {
                            showSettingsToast(data.error || 'Error refreshing cache', 'error');
                        }
                    })
                    .catch(error => {
                        document.getElementById('settingsLoading').style.display = 'none';
                        showSettingsToast('Network error occurred', 'error');
                        console.error('Cache refresh error:', error);
                    });
            });

            // Form submission handling
            document.querySelectorAll('.settings-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    submitSettingsForm(this);
                });
            });

            // Reset buttons
            document.querySelectorAll('.reset-group').forEach(button => {
                button.addEventListener('click', function () {
                    const group = this.dataset.group;
                    if (confirm(`Reset all ${group} settings to defaults?`)) {
                        resetSettingsGroup(group);
                    }
                });
            });

            // Individual setting reset
            document.querySelectorAll('.reset-setting').forEach(button => {
                button.addEventListener('click', function () {
                    const key = this.dataset.key;
                    if (confirm(`Reset this setting to default value?`)) {
                        resetSingleSetting(key);
                    }
                });
            });

            // Toast function
            window.showSettingsToast = function (message, type = 'success') {
                if (type === 'success') {
                    document.getElementById('successMessage').textContent = message;
                    successToast.show();
                } else {
                    document.getElementById('errorMessage').textContent = message;
                    errorToast.show();
                }
            };

            // Auto-hide alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });

        // Submit settings form
        function submitSettingsForm(form) {
            const formData = new FormData(form);
            const data = Object.fromEntries(formData);
            const group = form.dataset.group;

            document.getElementById('settingsLoading').style.display = 'flex';

            fetch(form.action, {
                method: form.method,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('settingsLoading').style.display = 'none';

                    if (data.success) {
                        showSettingsToast(data.message || 'Settings saved successfully!', 'success');

                        // Update form values
                        Object.keys(data).forEach(key => {
                            const input = form.querySelector(`[name="${key}"]`);
                            if (input) {
                                if (input.type === 'checkbox') {
                                    input.checked = Boolean(data[key]);
                                } else {
                                    input.value = data[key];
                                }
                            }
                        });
                    } else {
                        showSettingsToast(data.error || 'Error saving settings', 'error');
                    }
                })
                .catch(error => {
                    document.getElementById('settingsLoading').style.display = 'none';
                    showSettingsToast('Network error occurred', 'error');
                    console.error('Form submission error:', error);
                });
        }

        // Reset settings group
        function resetSettingsGroup(group) {
            document.getElementById('settingsLoading').style.display = 'flex';

            fetch(`/admin/settings/${group}/reset-all`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('settingsLoading').style.display = 'none';

                    if (data.success) {
                        showSettingsToast(data.message || 'Settings reset to defaults!', 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showSettingsToast(data.error || 'Error resetting settings', 'error');
                    }
                })
                .catch(error => {
                    document.getElementById('settingsLoading').style.display = 'none';
                    showSettingsToast('Network error occurred', 'error');
                    console.error('Reset error:', error);
                });
        }

        // Reset single setting
        function resetSingleSetting(key) {
            document.getElementById('settingsLoading').style.display = 'flex';

            fetch(`/admin/settings/${key}/reset`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('settingsLoading').style.display = 'none';

                    if (data.success) {
                        showSettingsToast('Setting reset to default!', 'success');

                        // Update the input value
                        const input = document.getElementById(`setting-${key.replace('.', '-')}`);
                        if (input && data.value !== undefined) {
                            if (input.type === 'checkbox') {
                                input.checked = Boolean(data.value);
                            } else {
                                input.value = data.value;
                            }
                        }
                    } else {
                        showSettingsToast(data.error || 'Error resetting setting', 'error');
                    }
                })
                .catch(error => {
                    document.getElementById('settingsLoading').style.display = 'none';
                    showSettingsToast('Network error occurred', 'error');
                    console.error('Single reset error:', error);
                });
        }
    </script>
@endpush