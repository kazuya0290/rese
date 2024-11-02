@extends('layouts.app')

@section('css') 
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" /> 
<link rel="stylesheet" href="{{ asset('css/my_page.css') }}"> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endsection 

@section('content') 
@if (Auth::check()) 
<div class="home-form"> 
    <h2 class="home-form__heading">{{ $user->name }}さんご利用ありがとうございます！！</h2> 

    <div class="panel-container"> 
        <div class="left-panel"> 
            <h3>予約状況</h3> 
            @foreach ($reservations as $index => $reservation) 
                <div class="reservation-item"> 
                    <h3> 
                        <span class="material-symbols-outlined" style="font-size: 24px; vertical-align: middle; margin-right: 8px;">restaurant</span> 
                        予約{{ $index + 1 }} 
                        <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" style="display: inline;" title="予約キャンセル・削除" onsubmit="return handleCancel(event, '{{ $reservation->shop->name }}');"> 
                            @csrf 
                            @method('DELETE') 
                            <button type="submit" class="cancel-button">×</button> 
                        </form>
                    </h3> 
                    <p class="reservation-details"> 
                        Shop: {{ $reservation->shop->name }}<br> 
                        Date: {{ \Carbon\Carbon::parse($reservation->date)->format('Y年m月d日') }}<br> 
                        Time: {{ \Carbon\Carbon::parse($reservation->time)->format('H:i') }}<br> 
                        Number: {{ $reservation->number_of_people }} 人 
                    </p> 
                    <p class="qr-code-link">
                        <button class="generate-qr-code" data-reservation-id="{{ $reservation->id }}" data-url="{{ route('reservation.qr', $reservation->id) }}">QRコードを発行する</button>
                    </p>
                    <div class="qr-img" id="qrcode-{{ $reservation->id }}"></div>
                    <h4 class="form-title">↓予約変更フォーム↓</h4> 
                    <form action="{{ route('reservations.update', $reservation->id) }}" method="POST" class="reservation-form" onsubmit="return handleUpdate(event, '{{ $reservation->shop->name }}');"> 
                        @csrf 
                        @method('PUT') 
                        <div class="reservation-actions"> 
                            <label for="time_{{ $index }}">日付日時変更：</label> 
                            <input type="datetime-local" id="time_{{ $index }}" name="time" value="{{ \Carbon\Carbon::parse($reservation->date . ' ' . $reservation->time)->format('Y-m-d\TH:i') }}" title="来店日時を変える場合は日付と日時を変更してください"> 
                        </div> 
                        <div class="reservation-actions"> 
                            <label for="number_of_people_{{ $index }}">人数変更：</label> 
                            <input type="number" id="number_of_people_{{ $index }}" name="number_of_people" value="{{ $reservation->number_of_people }}" min="1" max="20" placeholder="人数" title="来店人数を変更する場合は人数を変更してください"> 
                            <span class="unit">人</span> 
                        </div> 
                        <button id="reservation-update-button_{{ $index }}" type="submit" disabled title="予約内容の変更を行ってください" class="reservation-update-button">予約変更</button> 
                    </form> 
                </div> 
            @endforeach 
        </div> 

        <div class="right-panel">
            <h3 class="favorite_title">お気に入り店舗</h3>
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
                                <span class="favorite-button" data-shop-id="{{ $shop->id }}">
                                    <i class="material-symbols-outlined favorite-icon {{ in_array($shop->id, $favorites) ? 'active' : '' }}" title="お気に入り登録">
                                        {{ in_array($shop->id, $favorites) ? 'favorite' : 'favorite_border' }}
                                    </i>
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    const initialDates = [];
    const initialPeople = [];

    @foreach ($reservations as $index => $reservation)
        initialDates.push("{{ \Carbon\Carbon::parse($reservation->date . ' ' . $reservation->time)->format('Y-m-d\TH:i') }}");
        initialPeople.push({{ $reservation->number_of_people }});
    @endforeach

    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date(); 
        const formattedNow = now.toISOString().slice(0, 16); // 現在の日時（フォーマット: YYYY-MM-DDTHH:MM）

        @foreach ($reservations as $index => $reservation)
            (function(index) {
                const timeInput = document.getElementById("time_" + index);
                const dateInput = document.getElementById("time_" + index);
                const peopleInput = document.getElementById("number_of_people_" + index);
                const updateButton = document.getElementById("reservation-update-button_" + index);
                
                // 予約日を配列に格納
                const isDuplicate = @json($reservations->pluck('date')->map(function($date) { return \Carbon\Carbon::parse($date)->format('Y-m-d'); }));

                // 日付の入力に対して、過去の日付と当日の現在時刻以前の時間を無効にする
                dateInput.setAttribute('min', formattedNow);

                dateInput.addEventListener("change", function() {
                    const selectedDate = new Date(dateInput.value);

                    // 当日を選択した場合は現在時刻以降のみ選択可能にする
                    if (selectedDate.toDateString() === now.toDateString()) {
                        dateInput.setAttribute('min', formattedNow); // 当日なら現在の時刻を最小値に設定
                    } else {
                        dateInput.setAttribute('min', new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1).toISOString().slice(0, 16)); // 未来の日付なら制限なし
                    }
                    checkInputs(index);
                });

                peopleInput.addEventListener("input", function() {
                    checkInputs(index);
                });

                function checkInputs(index) {
    const dateChanged = timeInput.value !== initialDates[index];  // 日付・時間の変更を確認
    const peopleChanged = peopleInput.value !== initialPeople[index].toString();  // 人数の変更を確認
    const isPastDate = new Date(timeInput.value) < new Date();  // 過去の日付かどうかを確認
    const selectedDate = new Date(timeInput.value).toISOString().slice(0, 10);  // 選択された日付（年月日）を取得
    const hasDuplicateReservation = isDuplicate.filter(date => date !== initialDates[index].slice(0, 10)).includes(selectedDate); // 他の予約日と重複しているか確認
    
    // 時間帯だけの変更の場合もdateChangedをtrueとする
    const originalDateTime = new Date(initialDates[index]);
    const selectedDateTime = new Date(timeInput.value);
    
    // 時間が変更されたかを個別に確認（同日でも時間のみの変更が可能になる）
      const timeChanged = originalDateTime.getTime() !== selectedDateTime.getTime();

    if (isPastDate) {
        updateButton.setAttribute('title', '過去の日付、または予約当日の過去の時刻が設定されています');
    } else if (hasDuplicateReservation) {
        updateButton.setAttribute('title', '他の予約日と日付が重複しています');
    } else {
        updateButton.setAttribute('title', '予約内容の変更を行ってください');
    }

    // 時間帯のみの変更も有効とするために、timeChangedも条件に追加
    updateButton.disabled = !(dateChanged || peopleChanged || timeChanged) || isPastDate || hasDuplicateReservation;
}

            })({{ $index }});
        @endforeach

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
    });

    function handleCancel(event, shopName) {
        if (confirm('本当にキャンセルまたは削除しますか？')) {
            alert(`${shopName}の予約キャンセル・削除が完了しました`);
            return true;
        } else {
            event.preventDefault();
            return false;
        }
    }

    function handleUpdate(event, shopName) {
        if (confirm('本当に変更しますか？')) {
            alert(`${shopName}の予約が変更されました`);
            return true;
        } else {
            event.preventDefault();
            return false;
        }
    }

     document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById("qr-code-modal");
        const closeModal = document.querySelector(".close");
        const modalQRCodeDiv = document.getElementById("modal-qrcode");

        document.querySelectorAll('.generate-qr-code').forEach(button => {
            button.addEventListener('click', function() {
                const reservationId = this.getAttribute('data-reservation-id');
                const qrCodeUrl = this.getAttribute('data-url');
                const qrCodeDiv = document.getElementById("qrcode-" + reservationId);

                generateQRCode(qrCodeUrl, qrCodeDiv);
            });
        });

        function generateQRCode(url, element) {
            element.innerHTML = ""; // 既存のQRコードをクリア
            new QRCode(element, {
                text: url,
                width: 128,
                height: 128
            });
        }
    });
    window.onload = function() {
        generateQRCode();
    };
</script>
@endif  
@endsection
