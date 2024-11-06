@extends('layouts.app')

@section('title', 'プロフィール設定画面 - coatechtechフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-settings">
    <h1 class="profile-settings__title">プロフィール設定</h1>

    <form>
        <div class="profile-settings__avatar">
            <img class="profile-settings__avatar-image" {{-- src="path/to/avatar-placeholder.png" --}} alt="アバター画像">

            <button class="profile-settings__avatar-button">画像を選択する</button>
        </div>

        <div class="profile-settings__field">
            <label class="profile-settings__label" for="username">ユーザー名</label>

            <input class="profile-settings__input" type="text" id="username" name="name">
        </div>

        <div class="profile-settings__field">
            <label class="profile-settings__label" for="postal_code">郵便番号</label>

            <input class="profile-settings__input" type="text" id="postal_code" name="postal_code">
        </div>

        <div class="profile-settings__field">
            <label class="profile-settings__label" for="address">住所</label>

            <input class="profile-settings__input" type="text" id="address" name="address">
        </div>

        <div class="profile-settings__field">
            <label class="profile-settings__label" for="building">建物名</label>

            <input class="profile-settings__input" type="text" id="building" name="building">
        </div>

        <button type="submit" class="profile-settings__submit-button">更新する</button>
    </form>
</div>
@endsection
