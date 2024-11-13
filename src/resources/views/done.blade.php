@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
@endsection

@section('content')
<div class="thanks-page">
  <div class="thanks-page__inner">
    <p class="thanks-page__message">ご予約ありがとうございます！！<br>当日のご来店心よりお待ちしております。</p>
    <form class="thanks-page__form" action="/mypage" method="get">
      <button class="return_btn btn">マイページへ戻る</button>
    </form>
  </div>
</div>
<div class="thanks-page-bg__inner">
  <span class="thanks-page-bg__text">Thank you</span>
</div>
@endsection