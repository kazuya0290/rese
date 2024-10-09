<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>

<body>
    <header>
        <div class="header__left">
            <div class="header__icon">
                <input id="drawer__input" class="drawer__hidden" type="checkbox">
               <label for="drawer__input" class="drawer__open">
                <span></span>
               </label>
                <nav class="contents">
                    <ul class="list">
                        <li class="menu"><a class="menu-link" href="/">Home</a></li>
                        @if (Auth::check())
                            <li class="menu"><a class="menu-link" href="/logout">Logout</a></li>
                            <li class="menu"><a class="menu-link" href="/mypage">Mypage</a></li>
                        @else
                            <li class="menu"><a class="menu-link" href="/register">Registration</a></li>
                            <li class="menu"><a class="menu-link" href="/login">Login</a></li>
                        @endif
                    </ul>
                </nav>
            </div>
            <div class="header_name">Rese</div>
        </div>
        @yield('header')
    </header>
    <main>
        @yield('content')
    </main>
</body>

</html>
