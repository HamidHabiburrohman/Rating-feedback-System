@props(['items' => []])

<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-2 text-sm">
        <li>
            <a href="{{ url('/') }}" class="text-gray-500 hover:text-orange-600">Home</a>
        </li>
        @foreach($items as $label => $url)
        <li class="flex items-center">
            <span class="text-gray-400 mx-2">/</span>
            @if($loop->last)
            <span class="text-gray-900 font-medium">{{ $label }}</span>
            @else
            <a href="{{ $url }}" class="text-gray-500 hover:text-orange-600">{{ $label }}</a>
            @endif
        </li>
        @endforeach
    </ol>
</nav>