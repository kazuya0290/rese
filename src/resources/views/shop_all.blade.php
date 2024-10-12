@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop_all.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
@endsection

@section('header')
    <form class="header__right" action="/" method="get">
        <div class="header__sort">
            <select name="sort" class="select-box__item sort__item" title="エリア選択">
                <option value="All_area">All area</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}">{{ $area->area }}</option>
                @endforeach
            </select>
            <select name="sort" class="select-box__item sort__item" title="ジャンル選択">
                <option value="All_genre">All genre</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}">{{ $genre->genre }}</option>
                @endforeach
            </select>
            
            <div class="search-box">
                <span class="material-symbols-outlined search-icon">search</span>
                <input class="search-form" type="text" placeholder="Search..." title="キーワード検索"/>
            </div>
        </div>
    </form>
@endsection
