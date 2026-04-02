@php $group = $group ?? 'general'; @endphp

{{-- Form untuk Save --}}
<form action="{{ route('admin.settings.group.update', $group) }}" method="POST" id="settings-form-{{ $group }}" class="settings-form">
    @csrf
    @method('PUT')
    
    {{-- Slot buat settings fields --}}
    @yield('settings-fields')
    
    <div class="settings-actions">
        <button type="button" class="btn btn-reset reset-group" data-group="{{ $group }}" onclick="confirmReset('{{ $group }}')">
            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset to Defaults
        </button>
        <button type="submit" class="btn btn-save save-group" data-group="{{ $group }}">
            <i class="bi bi-check-lg me-1"></i>Save {{ ucfirst($group) }} Settings
        </button>
    </div>
</form>

{{-- Form terpisah untuk Reset (karena method DELETE) --}}
<form action="{{ route('admin.settings.group.reset', $group) }}" method="POST" id="reset-form-{{ $group }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmReset(group) {
    if (confirm('Yakin reset semua setting di grup ini ke default?')) {
        document.getElementById('reset-form-' + group).submit();
    }
}
</script>