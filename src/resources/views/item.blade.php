@extends('layouts.app')

@section('title', '商品詳細画面 - COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="product-detail">
    <div class="product-detail__container">
        <!-- 商品画像 -->
        <div class="product-detail__image">
            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
        </div>

        <!-- 商品情報 -->
        <div class="product-detail__info">
            <h2 class="product-detail__title">{{ $item->name }}</h2>

            <p class="product-detail__brand">ブランド名: {{ $item->brand }}</p>

            <p class="product-detail__price">¥{{ number_format($item->price) }} <span class="product-detail__tax">(税込)</span></p>

            <div class="product-detail__actions">
                <!-- いいね機能 -->
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

                <!-- コメント数表示 -->
                <div class="product-detail__icon">
                    <i class="bi bi-chat"></i>

                    <p>{{ $comments->count() }}</p>
                </div>
            </div>

            <!-- 購入手続き -->
            @guest
                <form action="{{ route('login') }}" method="GET">
                    <button class="product-detail__purchase-button" type="submit">購入手続きへ</button>
                </form>
            @endguest

            @auth
                <form action="{{ route('purchase', ['item' => $item]) }}" method="GET">
                    <button class="product-detail__purchase-button" type="submit">購入手続きへ</button>
                </form>
            @endauth

            <!-- 商品についての各セクション -->
            <section class="product-detail__section">
                <h3 class="product-detail__section-title">商品説明</h3>

                <p class="product-detail__description">{{ $item->description }}</p>
            </section>

            <section class="product-detail__section">
                <h3 class="product-detail__section-title">商品の情報</h3>

                <div class="product-detail__category">
                    <p>カテゴリー</p>

                    <div class="product-detail__category-box">
                        @foreach ($item->categories as $category)
                            <span class="product-detail__category-tag">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="product-detail__condition">
                    <p>商品の状態</p>

                    <span class="product-detail__condition-detail">{{ $item->condition->name }}</span>
                </div>
            </section>

            <!-- コメント機能 -->
            <section class="product-detail__comments">
                <h3 class="product-detail__section-title">コメント ({{ $comments->count() }})</h3>

                @foreach ($comments as $comment)
                    <div class="product-detail__comment">
                        <div class="product-detail__comment-box1">
                            <img class="product-detail__comment-avatar" src="{{ $profile && $profile->image_path ? asset('storage/' . $profile->image_path) : '' }}" alt="プロフィール画像">

                            <p class="product-detail__comment-username">
                                {{ $comment->user->name }}
                            </p>
                        </div>

                        <div class="product-detail__comment-box2">
                            <p class="product-detail__comment-text">
                                {{ $comment->content }}
                            </p>
                        </div>
                    </div>
                @endforeach

                @guest
                    <form class="product-detail__comment-form" action="{{ route('login') }}" method="GET">
                @endguest

                @auth
                    <form class="product-detail__comment-form" action="{{ route('comment', ['item' => $item->id]) }}" method="POST">
                        @csrf
                @endauth

                        <label class="product-detail__comment-label" for="comment">商品へのコメント</label>

                        <textarea class="product-detail__comment-input" name="content" id="comment" rows="10"></textarea>

                        @error('content')
                            <span class="error-message">{{ $message }}</span>
                        @enderror

                        <button class="product-detail__comment-button" type="submit">コメントを送信する</button>
                    </form>
            </section>
        </div>
    </div>
</div>
@endsection