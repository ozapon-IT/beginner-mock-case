@extends('layouts.app')

@section('title', '商品詳細画面 - COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('header')
<x-header />
@endsection

@section('main')
<main>
    <!-- セッションメッセージ -->
    <x-alert type="error" :message="session('error')" />

    <div class="product-detail">
        <!-- 商品画像 -->
        <div class="product-detail__image">
            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
        </div>

        <!-- 商品情報 -->
        <div class="product-detail__info">
            <h2 class="product-detail__title">{{ $item->name }}</h2>

            <p class="product-detail__brand">ブランド名: {{ $item->brand }}</p>

            <p class="product-detail__price">
                ¥{{ number_format($item->price) }} <span class="product-detail__tax">(税込)</span>
            </p>

            <div class="product-detail__actions">
                <!-- いいね機能 -->
                <x-like-button :item="$item" />

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
                    <x-comment-component :comment="$comment" />
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

                        <x-validation-error field="content" />

                        <button class="product-detail__comment-button" type="submit">コメントを送信する</button>
                    </form>
            </section>
        </div>
    </div>
</main>
@endsection