@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">
@endsection

@section('content')
<div class="shop-detail-container">

    <div class="back-button-container">
        <a href="{{ route('shop.index') }}" class="back-button" title="店舗一覧に戻る">&lt;</a>
    </div>
   
    <div class="save-image-container">
        <a href="{{ asset($shop->image) }}" download="{{ $shop->name }}.jpg">
            <button type="button">画像を保存する</button>
        </a>
    </div>
   
    <div class="shop-info">
        <h1>{{ $shop->name }}</h1>
        <img src="{{ $shop->image }}" alt="{{ $shop->name }}" class="shop-image">
        <p>#{{ $shop->area->area }} #{{ $shop->genre->genre }}</p>
        <p>{{ $shop->description }}</p>
    </div>

    <div class="reservation-form">
        <h2>予約</h2>
        @auth
        <form id="reservation-form" action="{{ route('reservation.store', ['id' => $shop->id]) }}" method="POST">
            @csrf
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">

            <label for="date">Date</label>
            <input type="date" name="date" id="date" value="{{ old('date') }}" title="来店予約日を入力してください" required>

            @if ($errors->any())
            <div class="error-messages">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <label for="time">Time</label>
            <input type="time" name="time" id="time" value="{{ old('time') }}" title="来店時間を入力してください" required>

            <label for="number_of_people">Number</label>
            <input type="number" name="number_of_people" id="number_of_people" value="{{ old('number_of_people') }}" required min="1" max="20" title="来店人数を入力してください">

            <div class="reservation-details detail-box">
                <p>Shop: <span>{{ $shop->name }}</span></p>
                <p>Date: <span id="selected-date">{{ old('date') ? \Carbon\Carbon::parse(old('date'))->format('Y年m月d日') : '' }}</span></p>
                <p>Time: <span id="selected-time">{{ old('time') }}</span></p>
                <p>Number: <span id="selected-number">{{ old('number_of_people') ? old('number_of_people') . ' 人' : '' }}</span></p>
            </div>

            
            <button type="submit" class="reservation-submit">通常予約する</button>
            <button id="stripe-button" type="submit" class="reservation-submit">Stripeで予約する</
        </form>
        @endauth

        @guest
        <p class="login-message">
            <a href="{{ route('login') }}">ログイン</a>すると予約が可能となります。
        </p>
        @endguest
    </div>
</div>
@endsection


<script src="https://checkout.stripe.com/checkout.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const dateInput = document.querySelector('input[name="date"]');
        dateInput.addEventListener('input', function() {
            let selectedDate = new Date(this.value);
            if (!isNaN(selectedDate)) {
                document.getElementById('selected-date').innerHTML = selectedDate.toLocaleDateString('ja-JP', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }
        });
        if (dateInput.value) {
            dateInput.dispatchEvent(new Event('input'));
        }

        const timeInput = document.querySelector('input[name="time"]');
        timeInput.addEventListener('input', function() {
            document.getElementById('selected-time').innerHTML = this.value;
        });
        if (timeInput.value) {
            timeInput.dispatchEvent(new Event('input'));
        }

        const numberInput = document.querySelector('input[name="number_of_people"]');
        numberInput.addEventListener('input', function() {
            document.getElementById('selected-number').innerHTML = this.value + ' 人';
        });
        if (numberInput.value) {
            numberInput.dispatchEvent(new Event('input'));
        }

        const stripeButton = document.getElementById('stripe-button');
        stripeButton.addEventListener('click', function(e) {
            e.preventDefault();
            const stripeHandler = StripeCheckout.configure({
                key: '{{ env('STRIPE_KEY') }}',
                locale: 'auto',
                currency: 'jpy',
                token: function(token) {
                    const form = document.getElementById('reservation-form');
                    const hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', token.id);
                    form.appendChild(hiddenInput);
                    form.submit();
                }
            });

            stripeHandler.open({
                name: '{{ $shop->name }} の予約',
                description: '{{ $shop->name }} の予約',
                amount: 1000
            });
        });
    });
</script>
