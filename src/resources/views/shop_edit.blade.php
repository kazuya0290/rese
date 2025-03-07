@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/shop_edit.css') }}">
@endsection

@section('content')
    <div class="edit__wrap">
        <div class="edit__header">
            店舗情報の更新
        </div>

        <div class="edit__content-wrap">
            <form id="shopEditForm" action="{{ isset($shop) && $shop->id ? route('shop.update', $shop->id) : route('shop.store') }}" method="post" enctype="multipart/form-data" class="edit__form">
                @csrf
                 <div class="edit__content">
                    <div class="edit__title vertical-center">
                        店舗名
                    </div>
                    <div class="edit__area">
                        <input type="text" name="name" id="name" class="edit__area-name" value="{{ $shop->name ?? '' }}">
                        @if ($errors->has('name'))
                        <div class="error-messages" style="color: red; font-weight: bold;">
                            @foreach ($errors->get('name') as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                        @endif
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
                        <textarea class="edit__area-textarea" name="description" id="description" rows="10">{{ old('description', $shop->description ?? '') }}</textarea>
                        @if ($errors->has('description'))
                        <div class="error-messages" style="color: red; font-weight: bold;">
                            @foreach ($errors->get('description') as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                        @endif
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
                        <p class="edit__area-message">現在イメージはありません</p>
                        @endif
                        <p class="edit__area-message">※変更する場合</p>
                        <input type="file" name="image" id="image" class="edit__area-file">
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
                    <button type="submit" class="form__button-btn" id="updateButton"title="変更が加えられておりません、変更を加えてください" disabled>更新</button>
                     @if (session('success'))
                        <script>
                        alert("{{ session('success') }}");
                        </script>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('shopEditForm');
            const updateButton = document.getElementById('updateButton');

            const initialValues = {
                name: form.elements['name'].value,
                area_id: form.elements['area_id'].value,
                genre_id: form.elements['genre_id'].value,
                description: form.elements['description'].value,
                image: form.elements['image'].value
            };

            form.addEventListener('input', function() {
                let isChanged = false;

                for (const [key, value] of Object.entries(initialValues)) {
                    if (form.elements[key].value !== value) {
                        isChanged = true;
                        break;
                    }
                }

                updateButton.disabled = !isChanged;

                if (updateButton.disabled) {
                updateButton.title = "変更が加えられておりません、変更を加えてください";
                updateButton.style.cursor = "not-allowed";
                } else {
                updateButton.removeAttribute("title");
                updateButton.style.cursor = "pointer";
                }
            });
        });
    </script>
@endsection
