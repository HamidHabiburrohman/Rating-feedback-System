@props(['id' => 'generateQrModal'])

<div
    x-data="generateQrModal('{{ route('admin.admin.qr-codes.generate') }}')"
    x-on:open-generate-modal.window="open()"
    x-show="isOpen"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="{{ $id }}-title"
    role="dialog"
    aria-modal="true"
>
    <div class="flex min-h-screen items-center justify-center p-4 text-center">
        <div
            x-show="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"
            x-on:click="close()"
            aria-hidden="true"
        ></div>

        <div
            x-show="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-lg transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all"
            x-on:click.outside="close()"
        >
            <div class="border-b border-slate-100 px-6 py-5">
                <h3 class="text-lg font-bold tracking-tight text-slate-900" id="{{ $id }}-title">
                    Generate QR Code
                </h3>
                <p class="mt-1 text-sm text-slate-500">
                    Pilih unit untuk membuat QR Code baru.
                </p>
            </div>

            <div class="px-6 py-5 space-y-4">
                <template x-if="errors.general">
                    <div class="rounded-xl bg-red-50 border border-red-100 p-4 flex gap-3">
                        <svg class="h-5 w-5 text-red-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm font-medium text-red-800" x-text="errors.general"></p>
                    </div>
                </template>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Unit</label>
                    <select
                        x-model="selectedUnitId"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#f8773c] focus:ring-2 focus:ring-[#f8773c]/10 outline-none transition-all bg-white"
                    >
                        <option value="">-- Pilih Unit --</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->code ?? 'No Code' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="rounded-xl bg-amber-50 border border-amber-100 p-4 flex gap-3">
                    <svg class="h-5 w-5 text-amber-500 shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.345 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h4 class="text-sm font-bold text-amber-800">Perhatian</h4>
                        <p class="mt-1 text-sm text-amber-700 leading-relaxed">
                            Jika unit sudah memiliki QR Code aktif, sistem akan menolak pembuatan baru. Gunakan tombol Regenerate pada tabel QR Codes.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50/50 px-6 py-4">
                <button
                    type="button"
                    x-on:click="close()"
                    x-bind:disabled="isLoading"
                    class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-all hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    Batal
                </button>
                <button
                    type="button"
                    x-on:click="generate()"
                    x-bind:disabled="isLoading || !selectedUnitId"
                    class="relative flex items-center justify-center gap-2 rounded-lg bg-[#f8773c] px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-orange-200 transition-all hover:bg-[#e55a2b] focus:outline-none focus:ring-2 focus:ring-[#f8773c] focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-80"
                >
                    <template x-if="isLoading">
                        <svg class="h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </template>
                    <span x-text="isLoading ? 'Generating...' : 'Generate QR Code'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('generateQrModal', (actionUrl) => ({
        isOpen: false,
        isLoading: false,
        selectedUnitId: '',
        errors: {},
        open() {
            this.isOpen = true;
            this.errors = {};
            this.selectedUnitId = '';
        },
        close() {
            this.isOpen = false;
            this.isLoading = false;
        },
        async generate() {
            if (!this.selectedUnitId) return;
            this.isLoading = true;
            this.errors = {};
            try {
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ unit_id: this.selectedUnitId })
                });
                const data = await response.json();
                if (response.ok && data.success) {
                    this.close();
                    window.location.reload();
                } else {
                    this.errors = { general: data.message || 'Gagal generate QR Code.' };
                }
            } catch (err) {
                this.errors = { general: 'Terjadi kesalahan jaringan. Silakan coba lagi.' };
            } finally {
                this.isLoading = false;
            }
        }
    }))
})
</script>