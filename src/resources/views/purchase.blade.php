@extends('layouts.app')

@section('title', '商品購入画面 - COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('header')
<x-header />
@endsection

@section('main')
<main>
    <!-- セッションメッセージ -->
    <x-alert type="error" :message="session('error')" />

    <div class="purchase">
        <!-- 購入商品 -->
        <section class="purchase__item">
            <img class="purchase__item-image" src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}">

            <div class="purchase__item-details">
                <h1 class="purchase__item-name">{{ $item->name }}</h1>

                <p class="purchase__item-price">¥ {{ number_format($item->price) }}</p>
            </div>
        </section>

        <form class="purchase__form" action="{{ route('purchase.item', ['item' => $item->id]) }}" method="POST">
            @csrf

            <!-- 支払い方法 -->
            <section class="purchase__payment">
                <h2 class="purchase__payment-title">支払い方法</h2>

                <div class="purchase__payment-method">
                    <select class="purchase__payment-select" id="payment-method-select" name="payment_method">
                        <option value="">選択してください</option>

                        <option value="コンビニ払い" {{ (old('payment_method', $payment_method) == 'コンビニ払い') ? 'selected' : '' }}>コンビニ払い</option>

                        <option value="カード払い" {{ (old('payment_method', $payment_method) == 'カード払い') ? 'selected' : '' }}>カード払い</option>
                    </select>
                </div>

                <x-validation-error field="payment_method" />
            </section>

            <!-- 配送先 -->
            <section class="purchase__shipping">
                <h2 class="purchase__shipping-title">配送先</h2>

                <p class="purchase__shipping-address">
                    @if (is_array($address))
                        〒 {{ $address['postal_code'] }} <br>
                        {{ $address['address'] }} <br>
                        {{ $address['building'] }}
                    @else
                        〒 {{ $profile->postal_code }} <br>
                        {{ $profile->address }} <br>
                        {{ $profile->building}}
                    @endif
                </p>

                <!-- 配送先情報の隠しフィールド -->
                @if (is_array($address))
                    <input type="hidden" name="postal_code" value="{{ $address['postal_code'] }}">
                    <input type="hidden" name="address" value="{{ $address['address'] }}">
                    <input type="hidden" name="building" value="{{ $address['building'] }}">
                @else
                    <input type="hidden" name="postal_code" value="{{ $profile->postal_code }}">
                    <input type="hidden" name="address" value="{{ $profile->address }}">
                    <input type="hidden" name="building" value="{{ $profile->building }}">
                @endif

                <x-validation-error field="postal_code" />

                <x-validation-error field="address" />

                <x-validation-error field="building" />

                <a class="purchase__shipping-change" id="change-address-link" href="{{ route('address', ['item' => $item->id]) }}">変更する</a>
            </section>

            <!-- 購入概要 -->
            <div class="purchase__summary">
                <div class="purchase__summary-item">
                    <p class="purchase__summary-label">商品代金</p>

                    <p class="purchase__summary-price">¥ {{ number_format($item->price) }}</p>
                </div>

                <div class="purchase__summary-item">
                    <p class="purchase__summary-label">支払い方法</p>

                    <p class="purchase__summary-method" id="selected-payment-method">選択してください</p>
                </div>

                <button class="purchase__buy-button" type="submit">購入する</button>
            </div>
        </form>
    </div>
</main>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var paymentSelect = document.getElementById('payment-method-select');
        var summaryMethod = document.getElementById('selected-payment-method');
        var changeAddressLink = document.getElementById('change-address-link');

        function updateSummary() {
            var selectedOption = paymentSelect.value;
            summaryMethod.textContent = selectedOption ? selectedOption : '選択してください';

            var baseUrl = "{{ route('address', ['item' => $item->id]) }}";
            if (selectedOption) {
                changeAddressLink.href = baseUrl + "?payment_method=" + encodeURIComponent(selectedOption);
            } else {
                changeAddressLink.href = baseUrl;
            }
        }

        paymentSelect.addEventListener('change', updateSummary);

        // ページロード時に初期値を反映
        updateSummary();
    });
</script>
@endsection