<!-- resources/views/layouts/components/logout-modal.blade.php -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-0">
                <div class="p-4 text-center">
                    <div class="mx-auto mb-3" 
                         style="width: 64px; height: 64px; background: linear-gradient(135deg, #fee2e2, #fecaca); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="ti ti-logout fs-3 text-danger"></i>
                    </div>
                    <h5 class="modal-title fw-semibold mb-2">Confirm Logout</h5>
                    <p class="text-muted mb-4">Are you sure you want to logout from the admin panel?</p>
                    
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger px-4" id="confirmLogout">
                                <span>Logout</span>
                                <span class="spinner-border spinner-border-sm d-none ms-2" id="logoutSpinner"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>