@extends('layouts.app')

@section('title', 'プロフィール設定画面 - COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
@if (session('error'))
    <div class="error-message">
        {{ session('error') }}
    </div>
@endif

<div class="profile-settings">
    <h1 class="profile-settings__title">プロフィール設定</h1>

    <form class="profile-settings__form" method="POST" action="{{ route('profile.update') }}">
        @method('PATCH')
        @csrf
        <div class="profile-settings__avatar">
            <img class="profile-settings__avatar-image" src="{{ $profile && $profile->image_path ? Storage::url($profile->image_path) : null }}" alt="{{ $profile && $profile->image_path ? 'プロフィール画像' : '' }}">

            <label class="profile-settings__avatar-button" for="image">
                画像を選択
                <input type="file" name="image_path" accept=".jpg,.jpeg,.png" id="image">
            </label>

            @error('image')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="profile-settings__group">
            <label class="profile-settings__label" for="username">ユーザー名</label>

            <input class="profile-settings__input" type="text" id="username" name="name" value="{{ old('name', $user->name) }}">

            @error('name')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="profile-settings__group">
            <label class="profile-settings__label" for="postal_code">郵便番号</label>

            <input class="profile-settings__input" type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}">

            @error('postal_code')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="profile-settings__group">
            <label class="profile-settings__label" for="address">住所</label>

            <input class="profile-settings__input" type="text" id="address" name="address" value="{{ old('address', $profile->address ?? '') }}">

            @error('address')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="profile-settings__group">
            <label class="profile-settings__label" for="building">建物名</label>

            <input class="profile-settings__input" type="text" id="building" name="building" value="{{ old('building', $profile->building ?? '') }}">

            @error('building')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <button class="profile-settings__button" type="submit">更新する</button>
    </form>
</div>
@endsection
