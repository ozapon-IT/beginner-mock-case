@extends('layouts.app')

@section('title', '取引チャット画面 - COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/trading.css') }}">
@endsection

@section('header')
<x-header :search="false" :nav="false" />
@endsection

@section('main')
<main>
    <div class="trading__container">
        <div class="trading__sidebar">
            <p>その他の取引</p>
            <nav class="trading__navigation">
                <ul>
                    <li><a href="">商品名</a></li>
                    <li><a href="">商品名</a></li>
                    <li><a href="">商品名</a></li>
                </ul>
            </nav>
        </div>

        <div class="trading__content">
            <section class="trading__header">
                <div class="trading__header-container">
                    <div class="trading__avatar trading__avatar--large"></div>
                    <h1 class="trading__heading">「ユーザー名」さんとの取引画面</h1>
                </div>
                <button class="trading__button trading__button--complete">取引を完了する</button>
            </section>

            <section class="trading__product-info">
                <div class="trading__product-image">商品画像</div>
                <div class="trading__product-details">
                    <h2 class="trading__subheading">商品名</h2>
                    <p>商品価格</p>
                </div>
            </section>

            <section class="trading__chat">
                <div class="trading__messages">
                    <div class="trading__message trading__message--left">
                        <div class="trading__user trading__user--left">
                            <div class="trading__avatar"></div>
                            <div class="trading__user-name">ユーザー名</div>
                        </div>

                        <p class="trading__message-body">購入しました。最後まで取引よろしくお願いします。</p>
                    </div>

                    <div class="trading__message trading__message--right">
                        <div class="trading__user trading__user--right">
                            <div class="trading__user-name">あなた</div>
                            <div class="trading__avatar"></div>
                        </div>

                        <p class="trading__message-body">ご購入ありがとうございます。発送まで今しばらくお待ちください。</p>

                        <div class="trading__message-links">
                            <a class="trading__message-link" href="">編集</a>
                            <a class="trading__message-link" href="">削除</a>
                        </div>
                    </div>
                </div>

                <div class="trading__chat-input">
                    <input class="trading__input" type="text" placeholder="取引メッセージを入力してください">

                    <label class="trading__label-button" for="image">
                        画像を追加
                        <input type="file" name="image_path" accept=".jpeg,.png" id="image">
                    </label>

                    <button class="trading__icon-button"><i class="bi bi-send"></i></button>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection