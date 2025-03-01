@extends('layouts.app')

@section('title', 'ログイン画面 - COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('header')
<x-header :search="false" :nav="false" />
@endsection

@section('main')
<main>
    <x-alert type="success" :message="session('success')" />

    <div class="login">
        <h1 class="login__title">ログイン</h1>

        <form class="login__form" action="{{ route('login') }}" method="POST">
            @csrf

            <div class="login__form-group">
                <label class="login__label" for="email">メールアドレス</label>

                <input class="login__input" type="text" id="email" name="email" value="{{ old('email') }}">

                <x-validation-error field="email" />
            </div>

            <div class="login__form-group">
                <label class="login__label" for="password">パスワード</label>

                <input class="login__input" type="password" id="password" name="password">

                <x-validation-error field="password" />
            </div>

            <button class="login__button" type="submit">ログインする</button>
        </form>

        <div class="login__register-link">
            <a href="{{ route('register') }}">会員登録はこちら</a>
        </div>
    </div>
</main>
@endsection