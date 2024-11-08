@extends('layouts.app')

@section('title', 'プロフィール画面 - COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="user">
    <img class="user__avatar-image" {{-- src="" alt="" --}}>

    <h2 class="user__username">{{ $user->name }}</h2>

    <a class="user__profile-link" href="{{ route('profile') }}">プロフィールを編集</a>
</div>


<div class="tabs">
    <div class="tabs__container">
        <a class="tabs__tab tabs__tab--active" href="#mypage?page=sell">出品した商品</a>

        <a class="tabs__tab" href="#mypage?page=buy">購入した商品</a>
    </div>
</div>

<div class="product-grid">
    <div class="product-grid__item">
        <div class="product-grid__image"><a href="{{-- route('item', ['id' => $item->id]) --}}">商品画像</a></div>

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