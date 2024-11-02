@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop_edit.css') }}">
@endsection

@section('content')
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="edit__wrap">
        <div class="edit__header">
            店舗情報の更新
        </div>

        <div class="edit__content-wrap">
            <form action="{{ isset($shop) && $shop->id ? route('shop.update', $shop->id) : route('shop.store') }}" method="post" enctype="multipart/form-data" class="edit__form">
                @csrf
                 <div class="edit__content">
                    <div class="edit__title vertical-center">
                        店舗名
                    </div>
                    <div class="edit__area">
                        <input type="text" name="name" class="edit__area-name" value="{{ $shop->name ?? '' }}" required>
                    </div>
                </div>

                <div class="edit__content">
                    <div class="edit__title vertical-center">
                        エリア
                    </div>
                    <div class="edit__area">
                        <select name="area_id" size="1" class="edit__area-select" required>
                            <option value="" {{ !isset($shop) ? 'selected' : '' }} disabled>-- 選択 --</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}" {{ (isset($shop) && $shop->area_id == $area->id) ? 'selected' : '' }}>{{ $area->area }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="edit__content">
                    <div class="edit__title vertical-center">
                        ジャンル
                    </div>
                    <div class="edit__area">
                        <select name="genre_id" size="1" class="edit__area-select" required>
                            <option value="" {{ !isset($shop) ? 'selected' : '' }} disabled>-- 選択 --</option>
                            @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}" {{ (isset($shop) && $shop->genre_id == $genre->id) ? 'selected' : '' }}>{{ $genre->genre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="edit__content textarea__content">
                    <div class="edit__title">
                        説明
                    </div>
                    <div class="edit__area textarea__area">
                        <textarea class="edit__area-textarea" name="description" rows="10" required>{{ old('description', $shop->description ?? '') }}</textarea>
                    </div>
                </div>

                <div class="edit__content input-file__content">
                    <div class="edit__title vertical-center">
                        イメージ
                    </div>
                  <div class="edit__area input-file__area">
                    @if (isset($shop) && $shop->image)
                        <a href="{{ Str::startsWith($shop->image, 'http') ? $shop->image : Storage::url($shop->image) }}" class="edit__area-link vertical-center">
                        現在のイメージ
                        </a>
                    @else
                        <p class="edit__area-message">現在イメージはありません。</p>
                    @endif
                        <p class="edit__area-message">※変更する場合</p>
                    <input type="file" name="image" class="edit__area-file">
                   </div>

                </div>

                <div class="form__button">
                    <a href="/" class="back-button">戻る</a>
                    <button type="submit" class="form__button-btn">{{ $shop ? '更新' : '登録' }}
                    </button>
                     @if (session('success'))
                        <script>
                        alert("{{ session('success') }}");
                        </script>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
