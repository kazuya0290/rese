@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/review.css') }}">
@endsection

@section('content')
<div class="review-container">
    <h1>{{ $shop->name }} のレビュー</h1>

    <form action="{{ route('review.store', ['shop_id' => $shop->id]) }}" method="POST">
        @csrf

        <div class="star-rating">
            <label for="rating">評価:</label>
            <div class="stars">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="star" data-value="{{ $i }}">☆</span>
                @endfor
                <input type="hidden" name="rating" id="rating" value="{{ old('rating') }}">
            </div>
                @error('rating')
                    <div class="error">{{ $message }}</div>
                @enderror
        </div>

        <label for="comment">コメント:</label>
        <textarea name="comment" id="comment" rows="4">{{ old('comment') }}</textarea>
                @error('comment')
                    <div class="error">{{ $message }}</div>
                @enderror
        <button type="submit">レビューを送信</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star');
        let ratingInput = document.getElementById('rating');
        let oldRating = "{{ old('rating') }}";

        if (oldRating) {
            fillStarsUpTo(oldRating);
            ratingInput.value = oldRating;
        }
        
        stars.forEach(star => {
            star.addEventListener('mouseover', function() {
                resetStars();
                fillStarsUpTo(this.getAttribute('data-value'));
            });

            star.addEventListener('click', function() {
                ratingInput.value = this.getAttribute('data-value');
                fillStarsUpTo(ratingInput.value); 
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
    });
</script>
@endsection
