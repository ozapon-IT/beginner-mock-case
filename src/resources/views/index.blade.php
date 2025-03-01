@extends('layouts.app')

@section('title', '商品一覧画面（トップ画面）- COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('header')
<x-header :current-tab="$currentTab" />
@endsection

@section('main')
<main>
    <x-tabs :tabs="[
        ['key' => 'recommend', 'label' => 'おすすめ', 'url' => route('top', ['search' => request('search'), 'tab' => 'recommend'])],
        ['key' => 'mylist', 'label' => 'マイリスト', 'url' => route('top', ['search' => request('search'), 'tab' => 'mylist'])],
    ]" :current-tab="$currentTab" />

    <div class="product-grid">
        @foreach ($items as $item)
            <x-product-card :item="$item" context="index" />
        @endforeach
    </div>
</main>
@endsection