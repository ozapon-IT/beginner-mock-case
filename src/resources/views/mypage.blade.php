@extends('layouts.app')

@section('title', 'プロフィール画面 - COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
@if (session('success'))
    <div class="success">
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="user">
    <img class="user__avatar-image" {{-- src="" alt="" --}}>

    <h2 class="user__username">{{ $user->name }}</h2>

    <a class="user__profile-link" href="{{ route('profile') }}">プロフィールを編集</a>
</div>


<div class="tabs">
    <div class="tabs__container">
        <a class="tabs__tab {{ $currentTab === 'sell' ? 'tabs__tab--active' : '' }}" href="{{ route('mypage', ['tab' => 'sell']) }}">出品した商品</a>

        <a class="tabs__tab {{ $currentTab === 'buy' ? 'tabs__tab--active' : '' }}" href="{{ route('mypage', ['tab' => 'buy']) }}">購入した商品</a>
    </div>
</div>

<div class="product-grid">
    @foreach ($items as $item)
        <div class="product-grid__item">
            <div class="product-grid__image">
                <a href="{{ route('item', ['item' => $item->id]) }}">
                    <img src="{{ $item->image_path }}" alt="{{ $item->name }}">
                </a>
            </div>
            <p class="product-grid__name">{{ $item->name }}</p>
        </div>
    @endforeach
</div>
@endsection