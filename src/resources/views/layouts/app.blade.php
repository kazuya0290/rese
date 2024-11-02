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
                            <!-- 店舗代表者のメニュー -->
                            <li class="nav__item"><a class="nav__item-link" href="{{ route('representative.index') }}">Reservation List</a></li>
                        <li class="nav__item">
                            <form id="logout-form" action="{{ route('representative.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a class="nav__item-link" href="#" id="logout-link">Logout</a>
                        </li>
                        @elseif (Auth::check())
                            <!-- 通常ユーザーのメニュー -->
                            <li class="nav__item">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <a class="nav__item-link" href="#" id="logout-link">Logout</a>
                            </li>
                            <li class="nav__item"><a class="nav__item-link" href="/mypage">Mypage</a></li>
                        @else
                            <!-- 未ログインユーザーのメニュー -->
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

        // メニュー表示の切り替え
        drawerInput.addEventListener('change', function() {
            if (this.checked) {
                menuLabel.title = '閉じる';
            } else {
                menuLabel.title = 'メニュー';
            }
        });

        // ログアウト確認ダイアログ
        logoutLink.addEventListener('click', function(event) {
            event.preventDefault(); // リンクのデフォルト動作を防止

            // 確認ダイアログを表示
            const result = confirm('ログアウトしますか？');

            // 「はい」がクリックされた場合
            if (result) {
                alert('ログアウトしました'); // アラートメッセージを表示
                document.getElementById('logout-form').submit(); // ログアウトフォームを送信
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
