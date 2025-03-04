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
                    <div class="trading__avatar trading__avatar--large">
                        @if ($item->user_id !== auth()->id())
                            <img src="{{ asset('storage/' . $item->user?->profile?->image_path) }}" alt="プロフィール画像">
                        @else
                            <img src="{{ asset('storage/' . $item->order?->user?->profile?->image_path) }}" alt="プロフィール画像">
                        @endif
                    </div>

                    @if ($item->user_id !== auth()->id())
                        <h1 class="trading__heading">{{ $item->user?->name }}さんとの取引画面</h1>
                    @else
                        <h1 class="trading__heading">{{ $item->order?->user?->name }}さんとの取引画面</h1>
                    @endif
                </div>
                <button class="trading__button trading__button--complete">取引を完了する</button>
            </section>

            <section class="trading__product-info">
                <div class="trading__product-image">
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">
                </div>
                <div class="trading__product-details">
                    <h2 class="trading__subheading">{{ $item->name }}</h2>
                    <p>¥{{ number_format($item->price) }}</p>
                </div>
            </section>

            <section class="trading__chat">
                <div class="trading__messages">
                    @foreach ($messages as $message)
                        @php
                            // ログインユーザー自身のメッセージかどうか
                            $isOwnMessage = $message->user_id === auth()->id();
                        @endphp

                        <div class="trading__message {{ $isOwnMessage ? 'trading__message--right' : 'trading__message--left' }}">
                            <div class="trading__user {{ $isOwnMessage ? 'trading__user--right' : 'trading__user--left' }}">
                                @if ($isOwnMessage)
                                    {{-- 自分の名前・アイコン --}}
                                    <div class="trading__user-name">{{ Auth::user()->name }}</div>
                                    <div class="trading__avatar">
                                        <img src="{{ asset('storage/' . Auth::user()->profile?->image_path) }}" alt="プロフィール画像">
                                    </div>
                                @else
                                    {{-- 相手の名前・アイコン --}}
                                    <div class="trading__avatar">
                                        <img src="{{ asset('storage/' . $message->user?->profile?->image_path) }}" alt="プロフィール画像">
                                    </div>
                                    <div class="trading__user-name">{{ $message->user?->name }}</div>
                                @endif
                            </div>

                            {{-- メッセージ本文 --}}
                            @if ($isOwnMessage)
                                {{-- 自分のメッセージ --}}
                                <textarea class="trading__message-body" name="body" form="message-edit-{{ $message->id }}" rows="1">{{ old('body', $message->body) }}</textarea>

                                @if ($message->image_path)
                                    <img class="trading__message-image" src="{{ asset('storage/' . $message->image_path) }}" alt="アップロード画像">
                                @endif

                                <div class="trading__message-links">
                                    <form class="trading__edit-form" action="{{ route('trading.message.update', ['item' => $item, 'message' => $message]) }}" method="POST" id="message-edit-{{ $message->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="trading__message-link" type="submit">編集</button>
                                    </form>
                                    <form class="trading__delete-form" action="{{ route('trading.message.destroy', ['item' => $item, 'message' => $message]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="trading__message-link" type="submit">削除</button>
                                    </form>
                                </div>
                            @else
                                {{-- 相手のメッセージ --}}
                                <p class="trading__message-body">{{ $message->body }}</p>

                                @if ($message->image_path)
                                    <img class="trading__message-image" src="{{ asset('storage/' . $message->image_path) }}" alt="アップロード画像">
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- メッセージ投稿フォーム --}}
                <div class="trading__chat-input">
                    <x-validation-error field="body" />
                    <x-validation-error field="image_path" />

                    <form class="trading__message-form" action="{{ route('trading.message.store', ['item' => $item]) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input class="trading__input" type="text" name="body" placeholder="取引メッセージを入力してください" value="{{ old('body') }}">

                        <label class="trading__label-button" for="image">
                            画像を追加
                            <input type="file" name="image_path" accept=".jpeg,.png" id="image">
                        </label>

                        <button class="trading__icon-button" type="submit"><i class="bi bi-send"></i></button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</main>
@endsection