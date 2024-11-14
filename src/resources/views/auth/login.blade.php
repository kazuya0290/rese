@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="title_header">{{ __('Login') }}</div>
    <div class="title_body">
        <form id="login-form" method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="row_mb-3 input-with-icon">
                <span class="icon material-symbols-outlined">mail</span>
                <input id="email" type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror" name="email" title="メールアドレス" value="{{ old('email') }}" autocomplete="email" autofocus>
            </div>
            @error('email')
                <div class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
            <div class="row_mb-3 input-with-icon">
                <span class="icon material-symbols-outlined">lock</span>
                <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" title="パスワード" autocomplete="current-password">
            </div>
            @error('password')
                <div class="invalid-feedback">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror

            <div class="row mb-0">
                <button id="login" type="submit" class="btn btn-primary" name="btn-login">
                    {{ __('ログイン') }}
                </button>
            </div>
        </form>
    </div>
</div>
    @guest
    <p class="admin-message">
    ※管理者の方は,<a href="{{ route('admin.index') }}" onclick="return checkAdminPasscode()">こちら</a>
</p>

    <p class="r-login-message">
    ※店舗代表者の方は<a href="{{ route('representative.login') }}">こちら</a>
    </p>
    @endguest

   <script>
    
    function checkAdminPasscode() {

        const isAuthenticated = sessionStorage.getItem("admin_authenticated");

       
        if (!isAuthenticated) {
            const passcode = prompt("管理者パスコードを入力してください");

            if (passcode === "admin") {  
                sessionStorage.setItem("admin_authenticated", true);
                return true;  
            } else {
                alert("パスコードが違います");
                return false; 
            }
        }
        return true;  
    }
</script>
@endsection

