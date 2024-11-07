@extends('layouts.app')

@section('title', '商品一覧画面（トップ画面）- COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="tabs">
    <div class="tabs__container">
        <a class="tabs__tab tabs__tab--active" href="{{ route('top') }}">おすすめ</a>

        @guest
        <a class="tabs__tab" href="{{ route('login') }}">マイリスト</a>
        @endguest

        @auth
        <a class="tabs__tab" href="#?tab=mylist">マイリスト</a>
        @endauth
    </div>
</div>

<div class="product-grid">
    <div class="product-grid__item">
        <div class="product-grid__image"><a href="{{ route('item') }}">商品画像</a></div>

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