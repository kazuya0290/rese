@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container">
    <div class="title_header">{{ __('Registration') }}</div>
    <div class="title_body">
        <form method="POST" action="{{ route('register') }}" id="register_form">
            @csrf
            
            <div class="row_mb-3 input-with-icon">
                <span class="material-symbols-outlined">person</span>
                <input id="name" type="text" placeholder="Name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" title="名前" autofocus>
            </div>
            @error('name')
                <div class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror

           
            <div class="row_mb-3 input-with-icon">
                <span class="material-symbols-outlined">mail</span>
                <input id="email" type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" title="メールアドレス">
            </div>
            @error('email')
                <div class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror

           
            <div class="row_mb-3 input-with-icon">
                <span class="material-symbols-outlined">lock</span>
                <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" title="パスワード">
            </div>
            @error('password')
                <div class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror

            
            <div class="row_mb-3 input-with-icon">
                <span class="material-symbols-outlined">enhanced_encryption</span> 
                <input id="password-confirm" type="password" placeholder="Password Confirmation" class="form-control" name="password_confirmation" autocomplete="new-password" title="パスワード確認用,">
            </div>

            
            <div class="row_mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('会員登録') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
