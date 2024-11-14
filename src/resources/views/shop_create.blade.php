@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop_create.css') }}">
@endsection

@section('content')
    <div class="create__wrap">
        <div class="create__header">
            店舗情報の作成
        </div>

        <div class="create__content-wrap">
            <form action="{{ route('shop.store') }}" method="post" enctype="multipart/form-data" class="create__form">
                @csrf
                <div class="create__content">
                    <div class="create__title vertical-center">
                        店舗名
                    </div>
                    <div class="create__area">
                        <input type="text" name="name" class="create__area-name" value="{{ old('name') }}">
                        @if ($errors->has('name'))
                        <div class="error-messages" style="color: red; font-weight: bold;">
                            @foreach ($errors->get('name') as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                
                <div class="create__content">
                    <div class="create__title vertical-center">
                        エリア
                    </div>
                    <div class="create__area">
                        <select name="area_id" class="create__area-select">
                            <option value="" selected disabled>-- 選択 --</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                    {{ $area->area }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('area_id'))
                        <div class="error-messages" style="color: red; font-weight: bold;">
                            @foreach ($errors->get('area_id') as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <div class="create__content">
                    <div class="create__title vertical-center">
                        ジャンル
                    </div>
                    <div class="create__area">
                        <select name="genre_id" class="create__area-select">
                            <option value="" selected disabled>-- 選択 --</option>
                            @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}" {{ old('genre_id') == $genre->id ? 'selected' : '' }}>
                                    {{ $genre->genre }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('genre_id'))
                        <div class="error-messages" style="color: red; font-weight: bold;">
                            @foreach ($errors->get('genre_id') as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <div class="create__content textarea__content">
                    <div class="create__title">説明</div>
                    <div class="create__area textarea__area">
                        <textarea class="create__area-textarea" name="description" rows="10">{{ old('description') }}</textarea>
                        @if ($errors->has('description'))
                        <div class="error-messages" style="color: red; font-weight: bold;">
                            @foreach ($errors->get('description') as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <div class="create__content input-file__content">
                    <div class="create__title vertical-center">イメージ</div>
                    <div class="create__area input-file__area">
                        <p class="create__area-message">画像を選択してください</p>
                        <input type="file" name="image" class="create__area-file">
                        @if ($errors->has('image'))
                        <div class="error-messages" style="color: red; font-weight: bold;">
                            @foreach ($errors->get('image') as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                        @endif
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
