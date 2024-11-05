@extends('layouts.app')

@section('title', '商品詳細画面 - coachtechフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="product-detail">
    <div class="product-detail__container">
        <div class="product-detail__image">商品画像</div>

        <div class="product-detail__info">
            <h2 class="product-detail__title">商品名がここに入る</h2>

            <p class="product-detail__brand">ブランド名</p>

            <p class="product-detail__price">¥47,000 <span class="product-detail__tax">(税込)</span></p>

            <div class="product-detail__actions">
                <div class="product-detail__icon">
                    <i class="bi bi-star"></i>

                    <p>3</p>
                </div>

                <div class="product-detail__icon">
                    <i class="bi bi-chat"></i>

                    <p>1</p>
                </div>
            </div>

            <button class="product-detail__button">購入手続きへ</button>

            <section class="product-detail__section">
                <h3 class="product-detail__section-title">商品説明</h3>

                <p class="product-detail__description">
                    カラー：グレー<br>
                    新品<br>
                    商品の状態は良好です。傷もありません。<br>
                    購入後、即発送いたします。
                </p>
            </section>

            <section class="product-detail__section">
                <h3 class="product-detail__section-title">商品の情報</h3>

                <p class="product-detail__category">
                    カテゴリー
                    <span class="product-detail__tag">洋服</span>
                    <span class="product-detail__tag">メンズ</span>
                </p>

                <p class="product-detail__condition">
                    商品の状態
                    <span class="product-detail__condition-detail">良好</span>
                </p>
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