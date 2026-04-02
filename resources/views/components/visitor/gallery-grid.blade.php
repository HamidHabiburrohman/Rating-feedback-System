@props(['images' => []])

<div class="gallery-grid-wrapper">
    @if(count($images) > 0)
        <div class="gallery-grid">
            @foreach($images as $image)
                @include('components.visitor.gallery-card', [
                    'image' => $image,
                    'title' => $image['title'] ?? null,
                    'empty' => false
                ])
            @endforeach
        </div>
    @else
        <div class="gallery-grid gallery-grid--empty">
            @for($i = 0; $i < 6; $i++)
                @include('components.visitor.gallery-card', [
                    'image' => null,
                    'title' => $i === 0 ? 'No Photos Available' : null,
                    'empty' => true
                ])
            @endfor
        </div>
    @endif
</div>