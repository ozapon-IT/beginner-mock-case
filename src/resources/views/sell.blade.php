@extends('layouts.app')

@section('title', '商品出品画面 - COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('header')
<x-header />
@endsection

@section('main')
<main>
    <div class="sell">
        <h1 class="sell__title">商品の出品</h1>

        <form class="sell__form" action="{{ route('sell.item') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- 商品画像 -->
            <section class="sell__section sell__section--image">
                <h3 class="sell__label">商品画像</h3>

                <img class="sell__image-preview" id="item-image-preview" src="" alt="商品のプレビュー画像">

                <div class="sell__image-upload">
                    <label class="sell__image-button" for="image">
                        画像を選択する
                        <input type="file" name="image_path" accept=".jpeg,.png" id="image">
                    </label>
                </div>

                <x-validation-error field="image_path" />
            </section>

            <!-- 商品の詳細 -->
            <section class="sell__section sell__section--details">
                <h2 class="sell__subtitle">商品の詳細</h2>

                <!-- カテゴリー -->
                <div class="sell__category">
                    <h3 class="sell__label">カテゴリー</h3>

                    <div class="sell__category-items">
                        @foreach ($categories as $category)
                            <label class="sell__category-item">
                                <input type="checkbox" name="category_id[]" value="{{ $category->id }}" {{ in_array($category->id, old('category_id', [])) ? 'checked' : '' }}>

                                <span>{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <x-validation-error field="category_id" />

                <!-- ブランド名 -->
                <div class="sell__brand">
                    <label class="sell__label" for="brand">ブランド名 <span class="sell__brand-optional">任意</span></label>

                    <input class="sell__input" type="text" id="brand" name="brand" value="{{ old('brand') }}">
                </div>

                <x-validation-error field="brand" />

                <!-- 商品の状態 -->
                <div class="sell__condition">
                    <h3 class="sell__label">商品の状態</h3>

                    <select class="sell__select" name="condition_id">
                        <option value="" disabled {{ old('condition_id') ? '' : 'selected' }}>選択してください</option>

                        @foreach ($conditions as $condition)
                            <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>{{ $condition->name }}</option>
                        @endforeach
                    </select>
                </div>

                <x-validation-error field="condition_id" />
            </section>

            <!-- 商品名と説明 -->
            <section class="sell__section sell__section--description">
                <h2 class="sell__subtitle">商品名と説明</h2>

                <div class="sell__field">
                    <label class="sell__label" for="name">商品名</label>

                    <input class="sell__input" type="text" id="name" name="name" value="{{ old('name') }}">
                </div>

                <x-validation-error field="name" />

                <div class="sell__field">
                    <label class="sell__label" for="description">商品の説明</label>

                    <textarea class="sell__textarea" id="description" name="description">{{ old('description') }}</textarea>
                </div>

                <x-validation-error field="description" />

                <div class="sell__field">
                    <label class="sell__label" for="price">販売価格</label>

                    <input class="sell__input" type="text" id="price" name="price" value="{{ old('price') }}">
                    <span class="sell__input--yen-sign">¥</span>
                </div>

                <x-validation-error field="price" />
            </section>

            <button class="sell__submit-button" type="submit">出品する</button>
        </form>
    </div>
</main>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var imageInput = document.getElementById('image');
    var imagePreview = document.getElementById('item-image-preview');

    imageInput.addEventListener('change', function(e) {
        var file = e.target.files[0];

        if (file) {
            var reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.add('sell__image-preview--visible');
            };

            reader.readAsDataURL(file);
        } else {
            // ファイルが選択されていない場合、プレビューを非表示にする
            imagePreview.src = '';
            imagePreview.classList.remove('sell__image-preview--visible');
        }
    });
});
</script>
@endsection