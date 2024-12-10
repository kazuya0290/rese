@extends('layouts.app')

@section('css')	
<link rel="stylesheet" href="{{ asset('css/review.css') }}">	
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" /> 
@endsection

@section('content')
    <div class="review-page1">
     <p class="shop-review">今回のご利用はいかがでしたか？</p>
    <div class="shop-list" style="width: 40%; float: left; margin-right: 80px; height:430px;">
        <div class="shop-item">
            @php
                $imageUrl = $shop->image;
                if (!str_starts_with($imageUrl, 'http')) {
                    $imageUrl = Storage::url($imageUrl);
                }
            @endphp
            <img src="{{ $imageUrl }}" alt="{{ $shop->name }}" class="shop-image" style="width: 100%; height: auto;">
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
                    <span class="favorite-button" data-shop-id="{{ $shop->id }}">
                        <i class="material-symbols-outlined favorite-icon {{ in_array($shop->id, $favorites) ? 'active' : '' }}" title="お気に入り登録">
                            {{ in_array($shop->id, $favorites) ? 'favorite' : 'favorite_border' }}
                        </i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div style="position: absolute; left: 43.5%; top: 0; bottom: 0; width: 2px; height: 700px; background-color: gray;"></div>

     <div class="review-page2" style="display: flex; justify-content: space-between;">
    <div class="review-container" style="width: 55%; float: left;">
        <h1>{{ isset($review) ? $shop->name . 'の口コミを編集' : $shop->name . 'の口コミを投稿' }}</h1>

        <form action="{{ isset($review) ? route('review.update', $review->id) : route('review.store', $shop->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if(isset($review))
                @method('PUT')
            @endif

            <div class="star-rating">
                <label for="rating">体験を評価してください:</label>
                <div class="stars">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="star" data-value="{{ $i }}">☆</span>
                    @endfor
                    <input type="hidden" name="rating" id="rating" value="{{ isset($review) ? $review->rating : old('rating') }}">
                </div>
                @error('rating')    
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="comment-container">
                <label for="comment">口コミを投稿:</label>
                <textarea name="comment" id="comment" rows="5">{{ isset($review) ? $review->comment : old('comment') }}</textarea>
                <div class="char-counter">
                    <span id="charCount">0</span>/400(最大文字数)
                </div>
                @error('comment')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <label>{{ isset($review) ? '画像の修正:' : '画像の挿入:' }}</label>
            <div class="image-upload-area" id="dropzone">
                <p id="file-name-display">クリックして画像を追加 またはドラッグアンドドロップ</p>
            <input type="file" name="image" id="image" accept="image/*" style="display: none;" onchange="displayFileName()">
            </div>

            @error('image')
                <div class="error">{{ $message }}</div>
            @enderror

            @if(isset($review) && $review->image)
            <div class="current-image">
                <label>現在の画像:</label>
                <img src="{{ Storage::url($review->image) }}?t={{ time() }}" alt="現在の口コミ画像" style="max-width: 200px; margin-top: 10px; margin-left:20px;">
            </div>
            @endif
            <div class="submit-button-container">
                <button type="submit" class="submit-button">
                    {{ isset($review) ? '更新する' : '投稿する' }}
                </button>
        </form>
    </div>
</div>
           
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating');
        
        function initializeStars() {
            
            const initialRating = {{ isset($review) ? $review->rating : (old('rating') ?: 0) }};
            
            resetStars();
            if (initialRating > 0) {
                fillStarsUpTo(initialRating);
                ratingInput.value = initialRating;
            }
        }

        stars.forEach(star => {
            star.addEventListener('mouseover', function() {
                resetStars();
                fillStarsUpTo(this.getAttribute('data-value'));
            });

            star.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                ratingInput.value = value;
                resetStars();
                fillStarsUpTo(value);
            });

            star.addEventListener('mouseout', function() {
                resetStars();
                if (ratingInput.value) { 
                    fillStarsUpTo(ratingInput.value);
                }
            });
        });

        function resetStars() {
            stars.forEach(star => {
                star.innerHTML = '☆';
            });
        }

        function fillStarsUpTo(value) {
            for (let i = 0; i < value; i++) {
                stars[i].innerHTML = '★';
            }
        }

        initializeStars();
    });

     document.addEventListener('DOMContentLoaded', function () {
        const commentTextarea = document.getElementById('comment');
        const charCount = document.getElementById('charCount');
        const maxLength = 400;

        charCount.textContent = commentTextarea.value.length;

        commentTextarea.addEventListener('input', function () {
            const currentLength = this.value.length;
            charCount.textContent = currentLength;

            
            if (currentLength > maxLength) {
                charCount.style.color = 'red'; 

            } else {
                charCount.style.color = '#555';
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('image');
        const fileNameDisplay = document.getElementById('file-name-display');

        dropzone.addEventListener('click', function () {
            fileInput.click();
        });

        dropzone.addEventListener('dragover', function (e) {
            e.preventDefault();
            dropzone.classList.add('dragging');
        });

        dropzone.addEventListener('dragleave', function () {
            dropzone.classList.remove('dragging');
        });

        dropzone.addEventListener('drop', function (e) {
            e.preventDefault();
            dropzone.classList.remove('dragging');
            const files = e.dataTransfer.files;

            if (files.length > 0) {
                fileInput.files = files;
                displayFileName();
                alert('画像が追加されました: ' + files[0].name);
            }
        });
        
        fileInput.addEventListener('change', function () {
            if (fileInput.files.length > 0) {
                displayFileName(); 
                alert('画像が追加されました: ' + fileInput.files[0].name);
            }
        });
    
        function displayFileName() {
            if (fileInput.files.length > 0) {
                fileNameDisplay.textContent = fileInput.files[0].name;
            } else {
                fileNameDisplay.textContent = 'クリックして画像を追加 またはドラッグアンドドロップ'; 
            }
        }
        displayFileName(); 
    });

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
</script>
@endsection
