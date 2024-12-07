@extends('layouts.app')

@section('css')	
<link rel="stylesheet" href="{{ asset('css/review.css') }}">	
@endsection

@section('content')
<div class="review-container">
    <h1>{{ isset($review) ? $shop->name . 'の口コミを編集' : $shop->name . 'の口コミを投稿' }}</h1>

    <form action="{{ isset($review) ? route('review.update', $review->id) : route('review.store', $shop->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        @if(isset($review))
            @method('PUT')
        @endif

        <div class="star-rating">
                <label for="rating">評価:</label>
            <div class="stars">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="star" data-value="{{ $i }}">☆</span>
                @endfor
                <input type="hidden" name="rating" id="rating"
                       value="{{ isset($review) ? $review->rating : old('rating') }}">
            </div>
            @error('rating')	
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="comment-container">
            <label for="comment">コメント:</label>
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

            @if(isset($imageUrl))
                <div class="current-image">
                    <label>現在の画像:</label>
                    <img src="{{ $imageUrl }}" alt="現在の口コミ画像" style="max-width: 200px;">
                </div>
            @endif
        <button type="submit" class="submit-button">
            {{ isset($review) ? '更新する' : '投稿する' }}
        </button>
    </form>
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

</script>
@endsection
