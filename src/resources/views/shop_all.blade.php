@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop_all.css') }}">
@endsection

@section('header')
    <form class="header__right" action="/" method="get">
        <div class="header__sort">
            <label class="select-box__label sort__label">
                <select name="sort" class="select-box__item sort__item">
                    <option value="high_rating" {{ request('sort') == 'high_rating' ? 'selected' : '' }}>評価が高い順</option>
                    <option value="low_rating" {{ request('sort') == 'low_rating' ? 'selected' : '' }}>評価が低い順</option>
                </select>
            </label>
        </div>