@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop_detail.css') }}">
@endsection

@section('content')
<div class="shop-detail-container">

     <!-- 店舗名の上に配置 -->
    <div class="back-button-container">
        <a href="{{ route('shop.index') }}" class="back-button" title="店舗一覧に戻る">&lt;</a>
    </div>

    <!-- 店舗情報を左側に配置 -->
    <div class="shop-info">
        <h1>{{ $shop->name }}</h1>
        <img src="{{ $shop->image }}" alt="{{ $shop->name }}" class="shop-image">
        <p>#{{ $shop->area->area }} #{{ $shop->genre->genre }}</p>
        <p>{{ $shop->description }}</p>
    </div>

    <!-- 予約フォームを右側に配置 -->
<div class="reservation-form">
    <h2>予約</h2>

        @auth
    <!-- ログインしている場合、予約フォームを表示 -->
     <form action="{{ route('reservation.store', ['id' => $shop->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="shop_id" value="{{ $shop->id }}">

        <label for="reservation_date">予約日</label>
        <input type="date" name="reservation_date" id="reservation_date" required>

        <label for="reservation_time">時間</label>
        <input type="time" name="reservation_time" id="reservation_time" required>

        <label for="number_of_people">人数</label>
        <input type="number" name="number_of_people" id="number_of_people" required min="1" max="20">

        <div class="reservation-details detail-box">
            <p>店舗名: <span>{{ $shop->name }}</span></p>
            <p>日付: <span id="selected-date"></span></p>
            <p>時間: <span id="selected-time"></span></p>
            <p>人数: <span id="selected-number"></span></p>
        </div>

        <button type="submit" class="reservation-submit">予約する</button>
     </form>
        @endauth

        @guest
        <!-- ログインしていない場合、メッセージを表示 -->
       <p class="login-message">
        <a href="{{ route('login') }}">ログイン</a>すると予約が可能となります。
       </p>
        @endguest
    </div>

</div>
@endsection

@section('js')
<script>
    document.querySelector('input[name="date"]').addEventListener('input', function() {
        document.getElementById('selected-date').textContent = this.value;
    });
    document.querySelector('input[name="time"]').addEventListener('input', function() {
        document.getElementById('selected-time').textContent = this.value;
    });
    document.querySelector('input[name="number_of_people"]').addEventListener('input', function() {
        document.getElementById('selected-number').textContent = this.value;
    });
</script>
@endsection
