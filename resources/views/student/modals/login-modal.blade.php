{{-- resources/views/student/modals/login-modal.blade.php --}}
<div class="modal-overlay" id="loginModal">
    <div class="modal-sheet">
        <div class="modal-handle"></div>
        <h3 class="modal-title">Login Mahasiswa</h3>
        <p class="modal-subtitle">Masukkan identifier Anda untuk melanjutkan.</p>

        <form action="{{ route('student.login.submit') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-(--text-soft) mb-1">Student Identifier</label>
                <input type="text" name="student_identifier" class="modal-input" placeholder="Contoh: NIM" required>
            </div>
            <div class="flex gap-2">
                <button type="button" class="btn-report-link flex-1 justify-center" onclick="closeLoginModal()">Batal</button>
                <button type="submit" class="btn-rate flex-1 justify-center">Login</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showLoginModal() {
        document.getElementById('loginModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeLoginModal() {
        document.getElementById('loginModal').classList.remove('open');
        document.body.style.overflow = '';
    }
</script>