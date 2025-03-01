@props(['item'])

<div class="product-detail__icon">
    @guest
        <form action="{{ route('login') }}" method="GET">
            <button type="submit">
                <i class="bi bi-star"></i>
            </button>
        </form>
    @endguest

    @auth
        @if($item->likes->contains('user_id', auth()->id()))
            <form action="{{ route('unlike', $item) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">
                    <i class="bi bi-star-fill"></i>
                </button>
            </form>
        @else
            <form action="{{ route('like', $item) }}" method="POST">
                @csrf
                <button type="submit">
                    <i class="bi bi-star"></i>
                </button>
            </form>
        @endif
    @endauth

    <p>{{ $item->likes->count() }}</p>
</div>