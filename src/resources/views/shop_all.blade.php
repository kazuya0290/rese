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
    <div class="shop-list">
        @foreach ($shops as $shop)
            <div class="shop-item">
                <img src="{{ $shop->image }}" alt="{{ $shop->name }}" class="shop-image">
                <div class="shop-details">
        <div class="shop-info">
            <h3>{{ $shop->name }}</h3>
            <p>
            @if ($shop->area) #{{ $shop->area->area }} @endif
            @if ($shop->genre) #{{ $shop->genre->genre }} @endif
            </p>
        </div>
    
           <div class="shop-actions">
    <a href="{{ route('shop.show', ['id' => $shop->id]) }}" class="details-button">詳しくみる</a>
    @if (Auth::check())
        <span class="favorite-button" data-shop-id="{{ $shop->id }}">
            <i class="material-symbols-outlined favorite-icon">favorite</i>
        </span>
    @else
        <span class="favorite-button disabled" onclick="alert('ログインしてください。 ログイン後、お気に入り追加機能が使用可能となります。');">
            <i class="material-symbols-outlined favorite-icon">favorite</i>
        </span>
    @endif
</div>
        </div>
      </div>
        @endforeach
    </div>
@endsection

@section('js')
    <script>
        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);  
                timeout = setTimeout(() => { func.apply(this, args); }, delay);
            };
        }

        function submitForm() {
            document.getElementById('filterForm').submit();
        }

        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', debounce(submitForm, 500));

        document.addEventListener('DOMContentLoaded', function() {
            const favoriteButtons = document.querySelectorAll('.favorite-button');

            favoriteButtons.forEach(button => {
                if (!button.classList.contains('disabled')) {
                    button.addEventListener('click', function() {
                        const icon = this.querySelector('.favorite-icon');
                        const shopId = this.getAttribute('data-shop-id');

                        
                        icon.classList.toggle('active');


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
    </script>
@endsection
