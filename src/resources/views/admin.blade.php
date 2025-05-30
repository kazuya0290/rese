@extends('layouts.app')

@section('css') 
<link rel="stylesheet" href="{{ asset('css/admin.css') }}"> 
@endsection

@section('content')
    <div class="container mt-5">
        <h1>管理者画面</h1>

        <section class="mt-4">
            <h2>店舗代表者の追加</h2>
            <form id="representativeForm" action="{{ route('admin.store.representative') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">代表者名:</label>
                    <input type="text" name="name" id="name" class="form-control mx-2" value="{{ old('name') }}">
                    <div class="error-messages" id="nameErrors"></div>
                </div>
                <div class="form-group">
                    <label for="email">メールアドレス:</label>
                    <input type="email" name="email" id="email" class="form-control mx-2" value="{{ old('email') }}">
                    <div class="error-messages" id="emailErrors"></div>
                </div>
                <div class="form-group">
                    <label for="password">パスワード:</label>
                    <input type="password" name="password" id="password" class="form-control mx-2">
                    <div class="error-messages" id="passwordErrors"></div>
                </div>
                <button type="submit" class="btn btn-primary">店舗代表者を追加</button>
            </form>
            <div id="representativeResult" class="mt-3 error-container"></div>
        </section>

        <hr class="my-5">
        
        <section class="mt-5">
            <h2>利用者へのお知らせメール送信</h2>
            <form id="notificationForm" action="{{ route('admin.send.notification') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="subject">件名:</label>
                    <input type="text" name="subject" id="subject" class="form-control mx-2" value="{{ old('subject') }}">
                    <div class="error-messages" id="subjectErrors"></div>
                </div>
                <div class="form-group">
                    <label for="message">メッセージ内容:</label>
                    <textarea name="message" id="message" class="form-control mx-2" rows="3">{{ old('message') }}</textarea>
                    <div class="error-messages-message" id="messageErrors"></div>
                </div>
                <button type="submit" class="btn btn-primary send-email-btn">メールを送信</button>
            </form>
            <div id="notificationResult" class="mt-3 error-container"></div>
        </section>

        <hr class="my-5">

        <section class="mt-5">
            <h2>一般ユーザーの全店舗の口コミの確認・削除</h2>
            <button id="show-reviews" type="button" class="review-show-button">全店舗の口コミを表示</button>
            <div id="review-modal" class="modal" style="display:none;">
                <div class="modal-content">
                    <span class="close" style="cursor:pointer;">&times;</span>
                    <h2>全店舗の口コミ一覧</h2>
                    <div class="review-list" id="review-list"></div>
                </div>
            </div>
        </section>

        <hr class="my-5">
    
   <h2 class="title">CSVインポートによる店舗追加</h2>

    <form id="csvImportForm" action="{{ route('admin.import.csv') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group2">
        <label for="csv_file">CSVファイルを選択:</label>
        <input type="file" name="csvFile" accept=".csv" class="input-file" required>
         @if ($errors->any())
        <div class="csv-error-messages">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </div>
        @endif
    </div>
        <button type="submit" class="btn btn-primary">インポート</button>
    </form>
        @if (session('success'))
            <script>
            alert(@json(session('success')));
            </script>
        @endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('representativeForm').addEventListener('submit', function (event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch('{{ route('admin.store.representative') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errors => {
                    throw errors;
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert(data.message); 
                document.getElementById('representativeForm').reset();
                document.getElementById('representativeResult').innerHTML = '';
                clearErrors(); 
            }
        })
        .catch(errors => {
            clearErrors();
            if (errors.errors) {
                for (const [field, messages] of Object.entries(errors.errors)) {
                    const errorField = document.getElementById(`${field}Errors`);
                    if (errorField) {
                        messages.forEach(message => {
                            errorField.innerHTML += `<p>${message}</p>`;
                        });
                    }
                }
            }
        });
    });

    document.getElementById('notificationForm').addEventListener('submit', function (event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch('{{ route('admin.send.notification') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errors => {
                    throw errors;
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert("メールを送信しました");
                document.getElementById('notificationForm').reset();
                document.getElementById('notificationResult').innerHTML = '';
                clearErrors(); 
            }
        })
        .catch(errors => {
            clearErrors();
            if (errors.errors) {
                for (const [field, messages] of Object.entries(errors.errors)) {
                    const errorField = document.getElementById(`${field}Errors`) || document.getElementById(`${field}Errors`.replace("message", "messageErrors"));
                    if (errorField) {
                        messages.forEach(message => {
                            errorField.innerHTML += `<p>${message}</p>`;
                        });
                    }
                }
            }
        });
    });

    function clearErrors() {
    const errorElements = document.querySelectorAll('.error-messages, .error-messages-message');
    errorElements.forEach(element => {
        element.innerHTML = '';
        });
    }
    
        const isAuthenticated = sessionStorage.getItem("admin_authenticated");
        if (!isAuthenticated) {
            alert("管理者パスコードが必要です");
            window.location.href = "{{ route('login') }}";
        }
});

    document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('review-modal');
    const closeModal = modal.querySelector('.close');
    const reviewList = document.getElementById('review-list');

    document.getElementById('show-reviews').addEventListener('click', function () {
        fetch('{{ route('admin.reviews.all') }}', {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            reviewList.innerHTML = '';
            data.reviews.forEach(review => {
                reviewList.innerHTML += `
                    <div class="review-item" data-review-id="${review.id}">
                        <p><strong>${review.user_name}</strong> - ${review.created_at_formatted}</p>
                        <p>【店舗名】${review.shop_name}</p>
                        <p>${review.comment}</p>
                        ${review.image ? `<img src="${review.image}" alt="レビュー画像" style="max-width: 200px;">` : ''}
                        <button class="delete-review-button" data-review-id="${review.id}">削除</button>
                    </div>`;
            });
            
            document.querySelectorAll('.review-item img').forEach(img => {
                img.addEventListener('click', function () {
                    const imageUrl = this.src;
                    window.open(imageUrl, '_blank');
                });
            });

            document.querySelectorAll('.delete-review-button').forEach(button => {
                button.addEventListener('click', function () {
                    const reviewId = this.getAttribute('data-review-id');
                    if (confirm('本当に削除しますか？')) {
                        deleteReview(reviewId);
                    }
                });
            });

            modal.style.display = 'block';
        });
    });

    closeModal.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    function deleteReview(reviewId) {
        fetch(`{{ url('/reviews') }}/${reviewId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('削除が完了しました');
                const reviewItem = document.querySelector(`.review-item[data-review-id="${reviewId}"]`);
                if (reviewItem) reviewItem.remove();
            } else {
                alert('削除に失敗しました: ' + data.message);
            }
        })
        .catch(error => {
            alert('削除中にエラーが発生しました: ' + error.message);
        });
    }
});
</script>
@endsection
