<div class="product-grid__item">
    <div class="product-grid__image">
        <a href="{{ route('item', ['item' => $item->id]) }}">
            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">

            @if ($label)
                <span class="product-grid__label">{{ $label }}</span>
            @endif
        </a>
    </div>

    <p class="product-grid__name">{{ $item->name }}</p>
</div>