@extends('layouts.app')

@section('title', '商品出品画面 - COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="listing">
    <h1 class="listing__title">商品の出品</h1>

    <form class="listing__form" action="#" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- 商品画像 -->
        <div class="listing__section listing__section--image">
            <h3 class="listing__label">商品画像</h3>

            <div class="listing__image-upload">
                <label class="listing__image-button" for="image">
                    画像を選択する
                    <input type="file" name="image_path" accept=".jpg,.jpeg,.png" id="image">
                </label>
            </div>
        </div>

        <!-- 商品の詳細 -->
        <div class="listing__section listing__section--details">
            <h2 class="listing__subtitle">商品の詳細</h2>

            <!-- カテゴリー -->
            <div class="listing__category">
                <h3 class="listing__label">カテゴリー</h3>

                <div class="listing__category-items">
                    <label class="listing__category-item" for="fashion">
                        ファッション
                        <input type="radio" name="category" value="fashion" id="fashion">
                    </label>

                    <label class="listing__category-item" for="electronics">
                        家電
                        <input type="radio" name="category" value="electronics" id="electronics">
                    </label>
                    <!-- 他のカテゴリも同様に追加 -->
                </div>
            </div>

            <!-- 商品の状態 -->
            <div class="listing__condition">
                <h3 class="listing__label">商品の状態</h3>

                <select class="listing__select" name="condition">
                    <option value="" disabled selected>選択してください</option>

                    <option value="new">新品</option>

                    <option value="used">中古</option>
                </select>
            </div>
        </div>

        <!-- 商品名と説明 -->
        <div class="listing__section listing__section--description">
            <h2 class="listing__subtitle">商品名と説明</h2>

            <div class="listing__field">
                <label class="listing__label" for="name">商品名</label>

                <input class="listing__input" type="text" id="name" name="name">
            </div>

            <div class="listing__field">
                <label class="listing__label" for="description">商品の説明</label>

                <textarea class="listing__textarea" id="description" name="description"></textarea>
            </div>

            <div class="listing__field">
                <label class="listing__label" for="price">販売価格</label>

                <input class="listing__input" type="text" id="price" name="price">
            </div>
        </div>

        <button class="listing__submit-button" type="submit">出品する</button>
    </form>
</div>
@endsection