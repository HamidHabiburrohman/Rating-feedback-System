@props(['tabs', 'active' => 'about'])

<div class="tab-nav">
    @foreach($tabs as $tab)
        <button 
            class="tab-nav__item {{ $active === $tab['id'] ? 'tab-nav__item--active' : '' }}"
            data-tab="{{ $tab['id'] }}"
            aria-selected="{{ $active === $tab['id'] ? 'true' : 'false' }}"
        >
            {{ $tab['label'] }}
        </button>
    @endforeach
</div>