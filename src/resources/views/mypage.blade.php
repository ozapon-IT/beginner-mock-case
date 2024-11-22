@extends('layouts.app')

@section('title', 'プロフィール画面 - COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('header')
<x-header />
@endsection

@section('main')
<main>
    <x-alert type="success" :message="session('success')" />

    <div class="user">
        <img class="user__avatar-image" src="{{ $profile && $profile->image_path ? asset('storage/' . $profile->image_path) : '' }}" alt="">

        <h2 class="user__username">{{ $user->name }}</h2>

        <a class="user__profile-link" href="{{ route('profile') }}">プロフィールを編集</a>
    </div>

    <x-tabs :tabs="[
        ['key' => 'sell', 'label' => '出品した商品', 'url' => route('mypage', ['tab' => 'sell'])],
        ['key' => 'buy', 'label' => '購入した商品', 'url' => route('mypage', ['tab' => 'buy'])],
    ]" :current-tab="$currentTab" />

    <div class="product-grid">
        @foreach ($items as $item)
            <x-product-card :item="$item" context="mypage" />
        @endforeach
    </div>
</main>
@endsection