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
                <label id="menuLabel" for="drawer__input" class="drawer__open" title="メニュー"><span></span></label>
                <nav class="nav__content">
                    <ul class="nav__list">
                        <li class="nav__item"><a class="nav__item-link" href="/">Home</a></li>
                         @if (Auth::guard('representative')->check())
                            <li class="nav__item"><a class="nav__item-link" href="{{ route('representative.index') }}">Reservation List</a></li>
                        <li class="nav__item">
                            <form id="logout-form" action="{{ route('representative.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a class="nav__item-link" href="#" id="logout-link">Logout</a>
                        </li>
                        @elseif (Auth::check())
                            <li class="nav__item">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <a class="nav__item-link" href="#" id="logout-link">Logout</a>
                            </li>
                            <li class="nav__item"><a class="nav__item-link" href="/mypage">Mypage</a></li>
                        @else
                            <li class="nav__item"><a class="nav__item-link" href="/register">Registration</a></li>
                            <li class="nav__item"><a class="nav__item-link" href="/login">Login</a></li>
                        @endif
                    </ul>
                </nav>
            </div>
            <div class="header__logo">Rese</div>
        </div>
        @yield('header')
    </header>

    <script>
        const drawerInput = document.getElementById('drawer__input');
        const menuLabel = document.getElementById('menuLabel');
        const logoutLink = document.getElementById('logout-link');

        drawerInput.addEventListener('change', function() {
            if (this.checked) {
                menuLabel.title = '閉じる';
            } else {
                menuLabel.title = 'メニュー';
            }
        });

        logoutLink.addEventListener('click', function(event) {
            event.preventDefault(); 

            
            const result = confirm('ログアウトしますか？');

            
            if (result) {
                alert('ログアウトしました'); 
                document.getElementById('logout-form').submit(); 
            }
        });

        @if (session('login_success'))
            alert('{{ session('login_success') }}');
        @endif
    </script>

    <main>
        @yield('content')
    </main>
</body>

</html>
