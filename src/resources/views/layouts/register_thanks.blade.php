@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/common.css')}}">
@endsection

@section('content')
<div class="thanks-page">
  <div class="thanks-page__inner">
    <p class="thanks-page__message">会員登録ありがとうございました！</p>
    <form class="thanks-page__form" action="/" method="get">
      <button class="return_btn btn">ログインする</button>
    </form>
  </div>
</div>
<div class="thanks-page-bg__inner">
  <span class="thanks-page-bg__text">Thank you</span>
</div>
@endsection