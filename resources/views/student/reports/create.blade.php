@extends('layouts.student.app')

@section('title', 'Report an Issue | Itenas Portal')

@section('content')
<main class="min-h-screen pt-32 pb-20 px-6 flex items-center justify-center">
    <div class="max-w-2xl w-full">

        {{-- Header --}}
        <div class="mb-8 text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-error-container text-on-error-container text-xs font-bold uppercase tracking-widest mb-4">
                Laporkan Masalah
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-on-surface tracking-tight leading-tight">
                Report an Issue
            </h1>
            <p class="mt-4 text-on-surface-variant text-lg max-w-md mx-auto">
                Rating untuk <span class="font-semibold text-on-surface">{{ $rating->unit->name }}</span>
            </p>
        </div>

        {{-- DEBUGGING CARD --}}
        <div class="mb-6 p-4 rounded-lg bg-yellow-50 border border-yellow-200">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-yellow-600">bug_report</span>
                <div class="flex-1">
                    <p class="font-bold text-yellow-800 text-sm mb-2">Debug Information:</p>
                    <div class="text-xs text-yellow-700 space-y-1">
                        <p><strong>Rating ID:</strong> {{ $rating->id }}</p>
                        <p><strong>Rating Tracking Code:</strong> {{ $rating->tracking_code }}</p>
                        <p><strong>Rating Unit ID:</strong> {{ $rating->unit_id }}</p>
                        <p><strong>Rating Unit Name:</strong> {{ $rating->unit->name }}</p>
                        <p><strong>Rating Student Identifier:</strong> {{ $rating->student_identifier }}</p>
                        <p><strong>Auth Student Identifier:</strong> {{ auth('student')->user()->student_identifier }}</p>
                        <p><strong>Student Check:</strong> {{ auth('student')->check() ? 'Logged In' : 'Not Logged In' }}</p>
                        <p><strong>Can Report:</strong> {{ app(\App\Services\Student\ReportService::class)->canReport($rating) ? 'Yes' : 'No' }}</p>
                        <p><strong>Route to Submit:</strong> {{ route('student.reports.store') }}</p>
                        <p><strong>CSRF Token Present:</strong> {{ csrf_token() ? 'Yes' : 'No' }}</p>
                        <p><strong>Form Action:</strong> {{ route('student.reports.store') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Error Alert --}}
        @if($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-error/10 border border-error/20">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-error">error</span>
                <div class="flex-1">
                    <p class="font-bold text-error text-sm">Please fix the following errors:</p>
                    <ul class="list-disc list-inside text-sm text-error/80 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 p-4 rounded-lg bg-error/10 border border-error/20">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-error">error</span>
                <p class="text-error text-sm">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        {{-- Rated Review Info Card --}}
        <div class="bg-surface-container-low rounded-2xl px-6 py-4 mb-6 flex items-center justify-between border border-outline-variant/10">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-primary">rate_review</span>
                <div>
                    <div class="text-sm font-bold text-on-surface">{{ $rating->tracking_code }}</div>
                    <div class="text-xs text-on-surface-variant">{{ $rating->created_at->format('d M Y') }}</div>
                </div>
            </div>
            <div class="flex text-primary gap-0.5">
                @for($i = 1; $i <= 5; $i++)
                    <span class="material-symbols-outlined text-sm"
                          style="font-variation-settings: 'FILL' {{ $i <= round($rating->overall_score) ? 1 : 0 }}, 'wght' 300, 'GRAD' 0, 'opsz' 24">
                        star
                    </span>
                @endfor
            </div>
        </div>

        {{-- Form Card --}}
        <div class="bg-surface-container-lowest rounded-lg p-8 md:p-12 shadow-[0_40px_80px_rgba(173,43,0,0.06)] border border-outline-variant/10">
            <form method="POST" action="{{ route('student.reports.store') }}" enctype="multipart/form-data" class="space-y-8" id="reportForm">
                @csrf
                <input type="hidden" name="rating_id" value="{{ $rating->id }}">

                {{-- Issue Category Pills --}}
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-widest text-on-surface-variant mb-4">
                        Issue Category
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach([
                            ['value' => 'technical',  'icon' => 'engineering',  'label' => 'Technical'],
                            ['value' => 'facility',   'icon' => 'lightbulb',    'label' => 'Facility'],
                            ['value' => 'network',    'icon' => 'router',       'label' => 'Network'],
                            ['value' => 'other',      'icon' => 'more_horiz',   'label' => 'Other'],
                        ] as $cat)
                        <label class="cursor-pointer group">
                            <input type="radio"
                                   name="category"
                                   value="{{ $cat['value'] }}"
                                   class="peer hidden"
                                   {{ old('category', 'technical') === $cat['value'] ? 'checked' : '' }}>
                            <div class="flex flex-col items-center justify-center p-4 rounded-2xl border border-outline-variant/20 bg-surface-container-low
                                        peer-checked:bg-primary/5 peer-checked:border-primary
                                        transition-all duration-200 text-on-surface-variant peer-checked:text-primary">
                                <span class="material-symbols-outlined mb-2" style="font-variation-settings: 'FILL' 0, 'wght' 200, 'GRAD' 0, 'opsz' 24">
                                    {{ $cat['icon'] }}
                                </span>
                                <span class="text-xs font-bold uppercase tracking-wider">{{ $cat['label'] }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('category')
                        <p class="text-error text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Subject --}}
                <div class="space-y-2">
                    <label for="title" class="block text-xs font-extrabold uppercase tracking-widest text-on-surface-variant ml-2">
                        Subject
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="Brief summary of the issue"
                        class="w-full px-6 py-4 bg-surface-container-low border-none rounded-lg text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/20 transition-all">
                    @error('title')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Detailed Description --}}
                <div class="space-y-2">
                    <label for="description" class="block text-xs font-extrabold uppercase tracking-widest text-on-surface-variant ml-2">
                        Detailed Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Please provide specific details including exact location or other relevant information..."
                        class="w-full px-6 py-4 bg-surface-container-low border-none rounded-lg text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary/20 transition-all resize-none">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Attachment (Optional) --}}
                <div class="space-y-2">
                    <label class="block text-xs font-extrabold uppercase tracking-widest text-on-surface-variant ml-2">
                        Attachment <span class="normal-case font-medium">(Optional)</span>
                    </label>
                    <label for="attachment"
                           class="flex flex-col items-center justify-center px-6 pt-6 pb-7 border-2 border-dashed border-outline-variant/30 rounded-lg bg-surface-container-low hover:bg-surface-container hover:border-primary/30 transition-all cursor-pointer group">
                        <span class="material-symbols-outlined text-on-surface-variant group-hover:text-primary transition-colors text-4xl mb-2">
                            cloud_upload
                        </span>
                        <div class="flex items-center gap-1 text-sm">
                            <span class="font-bold text-primary">Upload a file</span>
                            <span class="text-on-surface-variant">or drag and drop</span>
                        </div>
                        <p class="text-xs text-on-surface-variant/60 mt-1 uppercase tracking-wider">PNG, JPG, PDF up to 10MB</p>
                        <input id="attachment" name="attachment" type="file" accept=".png,.jpg,.jpeg,.pdf" class="sr-only">
                    </label>
                    @error('attachment')
                        <p class="text-error text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Debug Form Data Preview --}}
                <div class="text-xs text-slate-400 p-3 bg-slate-100 rounded-lg">
                    <p class="font-bold mb-1">Form Data akan dikirim:</p>
                    <p>rating_id: {{ $rating->id }}</p>
                    <p>category: [akan diambil dari radio button]</p>
                    <p>title: [dari input]</p>
                    <p>description: [dari textarea]</p>
                    <p>attachment: [optional file]</p>
                </div>

                {{-- Submit --}}
                <div class="pt-4">
                    <button type="submit"
                        class="w-full py-5 rounded-full bg-gradient-to-r from-primary to-primary-container text-white font-bold text-lg shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all duration-300 flex items-center justify-center gap-2">
                        Submit Report
                        <span class="material-symbols-outlined">send</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Info Note --}}
        <div class="mt-8 flex items-center justify-center gap-2 text-xs text-on-surface-variant">
            <span class="material-symbols-outlined text-base">info</span>
            <span>Laporan akan diproses oleh admin dalam waktu 1×24 jam</span>
        </div>

    </div>
</main>
@endsection

@push('scripts')
<script>
    // Debug form submission
    document.getElementById('reportForm')?.addEventListener('submit', function(e) {
        console.log('Form submitted');
        console.log('Form action:', this.action);
        console.log('Form method:', this.method);
        
        const formData = new FormData(this);
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + (pair[0] === 'attachment' ? pair[1]?.name || 'no file' : pair[1]));
        }
    });
    
    // Preview file name when selected
    document.getElementById('attachment')?.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const fileName = this.files[0].name;
            const fileSize = (this.files[0].size / 1024).toFixed(2);
            const label = document.querySelector('label[for="attachment"]');
            if (label) {
                const existingText = label.querySelector('.flex.items-center.gap-1.text-sm');
                if (existingText) {
                    const fileNameSpan = document.createElement('span');
                    fileNameSpan.className = 'text-xs text-primary mt-1 block';
                    fileNameSpan.textContent = `Selected: ${fileName} (${fileSize} KB)`;
                    
                    const oldSpan = label.querySelector('.text-xs.text-primary');
                    if (oldSpan) oldSpan.remove();
                    label.appendChild(fileNameSpan);
                }
            }
        }
    });
</script>
@endpush