@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('メールアドレス') }} ({{ Auth::user()->email }}) {{ __('にメールを送信しました') }}
                </div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('新しい認証リンクがメールアドレスに送信されました') }}
                        </div>
                    @endif

                    {{ __('続行する前に、メール内の認証リンクを確認してください。') }}
                    {{ __('もしメールを受け取っていない場合は') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('再送信するにはこちらをクリック') }}</button>。
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
