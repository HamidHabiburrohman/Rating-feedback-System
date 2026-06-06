{{-- resources/views/student/profile/sessions.blade.php --}}
@extends('layouts.student.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('student.profile.show') }}" class="text-primary hover:underline flex items-center gap-1">
            <span class="material-symbols-outlined">arrow_back</span> Back to Profile
        </a>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl shadow-xl overflow-hidden">
        <div class="px-6 py-5 border-b border-outline-variant/20 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-on-surface">Active Sessions</h2>
                <p class="text-on-surface-variant text-sm">Sessions where you're logged in (last 30 minutes activity)</p>
            </div>
            <button id="terminateAllBtn" class="bg-error text-white px-4 py-2 rounded-full text-sm font-bold hover:bg-error/80 transition">
                Terminate All Other Sessions
            </button>
        </div>

        @include('student.profile.partials.sessions-list', ['sessions' => $sessions])
    </div>
</div>

@push('scripts')
<script>
    function terminateSession(sessionId) {
        if (!confirm('Are you sure you want to terminate this session?')) return;
        fetch('{{ route("student.profile.terminate-session", "") }}/' + sessionId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(err => alert('Error terminating session'));
    }

    document.getElementById('terminateAllBtn')?.addEventListener('click', function() {
        if (!confirm('Terminate all other sessions? You will be logged out from other devices.')) return;
        fetch('{{ route("student.profile.terminate-all") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(err => alert('Error terminating sessions'));
    });
</script>
@endpush
@endsection