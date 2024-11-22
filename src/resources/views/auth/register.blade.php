@extends('layouts.app')

@section('title', '会員登録画面 - COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('header')
<x-header :search="false" :nav="false" />
@endsection

@section('main')
<main>
    <div class="register">
        <h1 class="register__title">会員登録</h1>

        <form class="register__form" action="{{ route('register') }}" method="POST">
            @csrf

            <div class="register__form-group">
                <label class="register__label" for="username">ユーザー名</label>

                <input class="register__input" type="text" id="username" name="name" value="{{ old('name') }}">

                <x-validation-error field="name" />
            </div>

            <div class="register__form-group">
                <label class="register__label" for="email">メールアドレス</label>

                <input class="register__input" type="text" id="email" name="email" value="{{ old('email') }}">

                <x-validation-error field="email" />
            </div>

            <div class="register__form-group">
                <label class="register__label" for="password">パスワード</label>

                <input class="register__input" type="password" id="password" name="password">

                <x-validation-error field="password" />
            </div>

            <div class="register__form-group">
                <label class="register__label" for="password_confirmation">確認用パスワード</label>

                <input class="register__input" type="password" id="password_confirmation" name="password_confirmation">

                <x-validation-error field="password_confirmation" />
            </div>

            <button class="register__button" type="submit">登録する</button>
        </form>

        <div class="register__login-link">
            <a href="{{ route('login') }}">ログインはこちら</a>
        </div>
    </div>
</main>
@endsection