@props(['category', 'value' => null, 'name' => "scores[{$category['id']}]"])

<div class="space-y-3" x-data="{ rating: {{ $value ?? 0 }} }">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-xl">star</span>
            <span class="font-semibold text-on-surface">{{ $category['name'] }}</span>
        </div>
        <span class="text-sm text-on-surface-variant" x-text="rating > 0 ? rating + '/5' : 'Belum dinilai'"></span>
    </div>
    
    <div class="flex gap-1">
        @for ($i = 1; $i <= 5; $i++)
            <button type="button" 
                @click="rating = {{ $i }}; $refs.input.value = {{ $i }}"
                class="focus:outline-none transition-transform hover:scale-110">
                <span class="material-symbols-outlined text-3xl"
                    :class="rating >= {{ $i }} ? 'text-primary fill-icon' : 'text-outline'"
                    x-bind:style="rating >= {{ $i }} ? 'font-variation-settings: \"FILL\" 1' : ''">
                    star
                </span>
            </button>
        @endfor
    </div>
    
    <input type="hidden" name="{{ $name }}" value="{{ $value ?? 0 }}" x-ref="input">
</div>