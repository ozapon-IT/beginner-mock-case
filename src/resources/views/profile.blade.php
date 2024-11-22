@extends('layouts.app')

@section('title', 'プロフィール設定画面 - COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('header')
<x-header />
@endsection

@section('main')
<main>
    <x-alert type="alert" :message="session('alert')" />

    <div class="profile-settings">
        <h1 class="profile-settings__title">プロフィール設定</h1>

        <form class="profile-settings__form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @method('PATCH')
            @csrf

            <div class="profile-settings__avatar">
                <img class="profile-settings__avatar-image" src="{{ $profile && $profile->image_path ? Storage::url($profile->image_path) : '' }}" alt="{{ $profile && $profile->image_path ? 'プロフィール画像' : '' }}" id="avatar-preview">

                <label class="profile-settings__avatar-button" for="image">
                    画像を選択
                    <input type="file" name="image_path" accept=".jpeg,.png" id="image">
                </label>

            </div>

            <x-validation-error field="image_path" />

            <div class="profile-settings__group">
                <label class="profile-settings__label" for="username">ユーザー名</label>

                <input class="profile-settings__input" type="text" id="username" name="name" value="{{ old('name', $user->name) }}">

                <x-validation-error field="name" />
            </div>

            <div class="profile-settings__group">
                <label class="profile-settings__label" for="postal_code">郵便番号</label>

                <input class="profile-settings__input" type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}">

                <x-validation-error field="postal_code" />
            </div>

            <div class="profile-settings__group">
                <label class="profile-settings__label" for="address">住所</label>

                <input class="profile-settings__input" type="text" id="address" name="address" value="{{ old('address', $profile->address ?? '') }}">

                <x-validation-error field="address" />
            </div>

            <div class="profile-settings__group">
                <label class="profile-settings__label" for="building">建物名</label>

                <input class="profile-settings__input" type="text" id="building" name="building" value="{{ old('building', $profile->building ?? '') }}">

                <x-validation-error field="building" />
            </div>

            <button class="profile-settings__button" type="submit">更新する</button>
        </form>
    </div>
</main>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var imageInput = document.getElementById('image');
    var avatarPreview = document.getElementById('avatar-preview');

    imageInput.addEventListener('change', function(e) {
        var file = e.target.files[0];

        if (file) {
            var reader = new FileReader();

            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
            };

            reader.readAsDataURL(file);
        } else {
            // ファイルが選択されていない場合、元の画像を表示
            avatarPreview.src = "{{ $profile && $profile->image_path ? Storage::url($profile->image_path) : '' }}";
        }
    });
});
</script>
@endsection