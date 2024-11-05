@extends('layouts.app')

@section('title', '商品一覧画面（トップ画面）- coachtechフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="tabs">
    <div class="tabs__container">
        <a class="tabs__tab tabs__tab--active" href="#">おすすめ</a>

        <a class="tabs__tab" href="#">マイリスト</a>
    </div>
</div>

<div class="product-grid">
    <div class="product-grid__item">
        <div class="product-grid__image"><a href="/item">商品画像</a></div>

        <p class="product-grid__name">商品名</p>
    </div>

    <div class="product-grid__item">
        <div class="product-grid__image">商品画像</div>

        <p class="product-grid__name">商品名</p>
    </div>

    <div class="product-grid__item">
        <div class="product-grid__image">商品画像</div>

        <p class="product-grid__name">商品名</p>
    </div>

    <div class="product-grid__item">
        <div class="product-grid__image">商品画像</div>

        <p class="product-grid__name">商品名</p>
    </div>

    <div class="product-grid__item">
        <div class="product-grid__image">商品画像</div>

        <p class="product-grid__name">商品名</p>
    </div>

    <div class="product-grid__item">
        <div class="product-grid__image">商品画像</div>

        <p class="product-grid__name">商品名</p>
    </div>

    <div class="product-grid__item">
        <div class="product-grid__image">商品画像</div>

        <p class="product-grid__name">商品名</p>
    </div>

    <div class="product-grid__item">
        <div class="product-grid__image">商品画像</div>

        <p class="product-grid__name">商品名</p>
    </div>
</div>
@endsection