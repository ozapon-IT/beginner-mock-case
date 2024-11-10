@extends('layouts.app')

@section('title', '商品購入画面 - COACHTECHフリマ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase">
    <div class="purchase__details">
        <section class="purchase__item">
            <img class="purchase__item-image" src="{{ $item->image_path }}" alt="{{ $item->name }}">

            <div class="purchase__item-details">
                <h1 class="purchase__item-name">{{ $item->name }}</h1>

                <p class="purchase__item-price">¥ {{ number_format($item->price) }}</p>
            </div>
        </section>

        <section class="purchase__payment">
            <h2 class="purchase__payment-title">支払い方法</h2>

            <select class="purchase__payment-select" id="payment-method-select">
                <option value="">選択してください</option>

                <option value="コンビニ払い">コンビニ払い</option>

                <option value="カード払い">カード払い</option>
            </select>
        </section>

        <section class="purchase__shipping">
            <h2 class="purchase__shipping-title">配送先</h2>

            <p class="purchase__shipping-address">
                @if (!isset($address) || empty($address))
                〒 {{ $profile->postal_code }} <br>
                {{ $profile->address }} <br>
                {{ $profile->building}}
                @else
                〒 {{ $address['postal_code'] }} <br>
                {{ $address['address'] }} <br>
                {{ $address['building'] }}
                @endif
            </p>

            <a class="purchase__shipping-change" href="{{ route('address') }}">変更する</a>
        </section>
    </div>

    <div class="purchase__summary">
        <div class="purchase__summary-item">
            <p class="purchase__summary-label">商品代金</p>

            <p class="purchase__summary-price">¥ {{ number_format($item->price) }}</p>
        </div>

        <div class="purchase__summary-item">
            <p class="purchase__summary-label">支払い方法</p>

            <p class="purchase__summary-method" id="selected-payment-method">選択してください</p>
        </div>

        <button class="purchase__buy-button">購入する</button>
    </div>
</div>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var paymentSelect = document.getElementById('payment-method-select');
        var summaryMethod = document.getElementById('selected-payment-method');

        function updateSummary() {
            var selectedOption = paymentSelect.value;
            summaryMethod.textContent = selectedOption ? selectedOption : '選択してください';
        }

        paymentSelect.addEventListener('change', updateSummary);

        // ページロード時に初期値を反映
        updateSummary();
    });
</script>
@endsection