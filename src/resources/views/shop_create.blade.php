@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop_edit.css') }}">
@endsection

@section('content')
    <div class="edit__wrap">
        <div class="edit__header">
            店舗情報の作成
        </div>

        <div class="edit__content-wrap">
            <form action="{{ route('shop.store') }}" method="post" enctype="multipart/form-data" class="edit__form">
                @csrf
                <div class="edit__content">
                    <div class="edit__title vertical-center">
                        店舗名
                    </div>
                    <div class="edit__area">
                        <input type="text" name="name" class="edit__area-name" value="{{ old('name') }}" required>
                    </div>
                </div>

                <!-- エリアとジャンルのセレクトボックス -->
                <div class="edit__content">
                    <div class="edit__title vertical-center">
                        エリア
                    </div>
                    <div class="edit__area">
                        <select name="area_id" class="edit__area-select" required>
                            <option value="" selected disabled>-- 選択 --</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->area }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="edit__content">
                    <div class="edit__title vertical-center">
                        ジャンル
                    </div>
                    <div class="edit__area">
                        <select name="genre_id" class="edit__area-select" required>
                            <option value="" selected disabled>-- 選択 --</option>
                            @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->genre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- 説明 -->
                <div class="edit__content textarea__content">
                    <div class="edit__title">説明</div>
                    <div class="edit__area textarea__area">
                        <textarea class="edit__area-textarea" name="description" rows="10" required>{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- イメージ -->
                <div class="edit__content input-file__content">
                    <div class="edit__title vertical-center">イメージ</div>
                    <div class="edit__area input-file__area">
                        <p class="edit__area-message">画像を選択してください</p>
                        <input type="file" name="image" class="edit__area-file">
                    </div>
                </div>

                <div class="form__button">
                    <a href="/" class="back-button">戻る</a>
                    <button type="submit" class="form__button-btn">登録</button>
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
