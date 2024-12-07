@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop_all.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
@endsection

@section('header')
    <form class="header__right" action="{{ route('shop.index') }}" method="get" id="filterForm">
        <div class="header__sort">
            <select name="area" class="select-box__item sort__item" title="エリア選択" onchange="document.getElementById('filterForm').submit()">
                <option value="All_area">All area</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ request('area') == $area->id ? 'selected' : '' }}>{{ $area->area }}</option>
                @endforeach
            </select>

            <select name="genre" class="select-box__item sort__item" title="ジャンル選択" onchange="document.getElementById('filterForm').submit()">
                <option value="All_genre">All genre</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>{{ $genre->genre }}</option>
                @endforeach
            </select>

            <div class="search-box">
                <span class="material-symbols-outlined search-icon">search</span>
                <input class="search-form" type="text" name="keyword" placeholder="Search..." value="{{ request('keyword') }}" id="searchInput" title="キーワード検索"/>
            </div>
        </div>
    </form>
@endsection

@section('content')
@if (Auth::guard('representative')->check())
<h2 class="home-form__heading">代表{{ $representative->name }}さんお疲れ様です！！</h2>
@endif
    <div class="shop-list">
        @foreach ($shops as $shop)
            <div class="shop-item">
                 @php
                    $imageUrl = $shop->image;
                    if (!str_starts_with($imageUrl, 'http')) {
                        $imageUrl = Storage::url($imageUrl);
                    }
                @endphp
                <img src="{{ $imageUrl }}" alt="{{ $shop->name }}" class="shop-image">
                <div class="shop-details">
                    <div class="shop-info">
                        <h3>{{ $shop->name }}</h3>
                        <p>
                            @if ($shop->area) #{{ $shop->area->area }} @endif
                            @if ($shop->genre) #{{ $shop->genre->genre }} @endif
                        </p>
                    </div>
                    <div class="shop-actions">
                        @if (Auth::check())
                            <a href="{{ route('shop.show', ['id' => $shop->id]) }}" class="details-button">詳しくみる</a>
                            <span class="favorite-button" data-shop-id="{{ $shop->id }}">
                                <i class="material-symbols-outlined favorite-icon {{ in_array($shop->id, $favorites) ? 'active' : '' }}" title="お気に入り登録">
                                    {{ in_array($shop->id, $favorites) ? 'favorite' : 'favorite_border' }}
                                </i>
                            </span>
                         @elseif (Auth::guard('representative')->check())
                            <a href="{{ route('shop.edit', ['id' => $shop->id]) }}" class="details-button">更新</a>
                            <span class="favorite-button disabled" onclick="alert('利用者(ユーザー)のみが利用可能な機能です');">
                                <i class="material-symbols-outlined favorite-icon">favorite_border</i>
                            </span>
                        @else
                            <a href="{{ route('shop.show', ['id' => $shop->id]) }}" class="details-button">詳しくみる</a>
                            <span class="favorite-button disabled" onclick="alert('ログインしてください。 ログイン後、お気に入り追加機能が使用可能となります。');">
                                <i class="material-symbols-outlined favorite-icon">favorite_border</i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if (Auth::guard('representative')->check())
    <div class="shop-add">
        <a href="{{ route('shop.create') }}" class="add-button"> 
            <span class="material-symbols-outlined add-icon">add</span> 店舗を追加
        </a>
    </div>
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const favoriteButtons = document.querySelectorAll('.favorite-button');

        favoriteButtons.forEach(button => {
            if (!button.classList.contains('disabled')) {
                const icon = button.querySelector('.favorite-icon');

                icon.setAttribute('title', icon.classList.contains('active') ? 'お気に入り削除' : 'お気に入り登録');

                button.addEventListener('click', function() {
                    icon.classList.toggle('active');
                    icon.setAttribute('title', icon.classList.contains('active') ? 'お気に入り削除' : 'お気に入り登録');

                    const shopId = button.getAttribute('data-shop-id');
                    const isFavorite = icon.classList.contains('active');

                    fetch(`/favorite/${shopId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({ is_favorite: isFavorite }),
                    });
                });
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
    const adminModeButton = document.getElementById("adminModeButton");
    const adminModeBanner = document.getElementById("adminModeBanner");

    const isAdminMode = @json(session('admin_mode') === true);

    // 初期表示: 管理者モードでなければボタンを非表示
    if (!isAdminMode) {
        adminModeButton.style.display = "none";
    }

    // 管理者モード切り替え処理
    adminModeButton.addEventListener("click", function () {
        const passcode = prompt("管理者パスコードを入力してください");

        if (passcode === "admin") {
            fetch('/set-admin-mode', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ passcode: passcode })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("管理者モードが有効になりました。");
                    location.reload(); // ページをリロードして状態を反映
                } else {
                    alert("パスコードが違います");
                }
            });
        }
    });

    // 管理者モードバナーの表示
    if (isAdminMode) {
        adminModeBanner.style.display = "block";
    }
});

    </script>
@endsection
