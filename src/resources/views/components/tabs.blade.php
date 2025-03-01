<div class="tabs">
    <div class="tabs__container">
        @foreach ($tabs as $tab)
            <a class="tabs__tab {{ $tab['isActive'] ? 'tabs__tab--active' : '' }}" href="{{ $tab['url'] }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>
</div>