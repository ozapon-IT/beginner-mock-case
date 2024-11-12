@extends('layouts.app')

@section('title', '送付先住所変更画面 - COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-change">
    <h1 class="address-change__title">住所の変更</h1>

    <form class="address-change__form" action="{{ route('address.change', ['item' => $item]) }}" method="POST">
        @csrf
        <div class="address-change__form-group">
            <label class="address-change__label" for="postal_code">郵便番号</label>

            <input class="address-change__input" type="text" id="postal_code" name="postal_code">

            @error('postal_code')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="address-change__form-group">
            <label class="address-change__label" for="address">住所</label>

            <input class="address-change__input" type="text" id="address" name="address">

            @error('address')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="address-change__form-group">
            <label class="address-change__label" for="building">建物名</label>

            <input class="address-change__input" type="text" id="building" name="building">

            @error('building')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <input type="hidden" name="payment_method" value="{{ $payment_method }}">

        <button class="address-change__button" type="submit">更新する</button>
    </form>
</div>
@endsection