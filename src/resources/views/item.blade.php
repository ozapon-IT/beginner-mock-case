@extends('layouts.app')

@section('title', '商品詳細画面 - COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="product-detail">
    <div class="product-detail__container">
        <div class="product-detail__image">
            <img src="{{ $item->image_path }}" alt="{{ $item->name }}">
        </div>

        <div class="product-detail__info">
            <h2 class="product-detail__title">{{ $item->name }}</h2>

            <p class="product-detail__brand">ブランド名</p>

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
                                    <i class="bi bi-star-fill"></i> <!-- いいね済みのアイコン -->
                                </button>
                            </form>
                        @else
                            <form action="{{ route('like', $item) }}" method="POST">
                                @csrf
                                <button type="submit">
                                    <i class="bi bi-star"></i> <!-- 未いいねのアイコン -->
                                </button>
                            </form>
                        @endif
                    @endauth

                    <p>{{ $item->likes->count() }}</p> <!-- いいね数を表示 -->
                </div>

                <!-- コメント機能 -->
                <div class="product-detail__icon">
                    <i class="bi bi-chat"></i>

                    <p>1</p>
                </div>
            </div>

            <button class="product-detail__button">購入手続きへ</button>

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

            <section class="product-detail__comments">
                <h3 class="product-detail__section-title">コメント (1)</h3>

                <div class="product-detail__comment">
                    <div class="product-detail__comment-box1">
                        <div class="product-detail__comment-avatar"></div>

                        <p class="product-detail__comment-username">admin</p>
                    </div>

                    <div class="product-detail__comment-box2">
                        <p class="product-detail__comment-text">
                        こちらにコメントが入ります。
                        </p>
                    </div>
                </div>

                <form class="product-detail__comment-form">
                    <label class="product-detail__comment-label" for="comment">商品へのコメント</label>

                    <textarea class="product-detail__comment-input" id="comment" rows="10"></textarea>

                    <button class="product-detail__comment-button" type="submit">コメントを送信する</button>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection