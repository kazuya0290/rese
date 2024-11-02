@extends('layouts.app')

@section('css') 
<link rel="stylesheet" href="{{ asset('css/admin.css') }}"> 
@endsection

@section('content')
    <div class="container mt-5">
        <h1>管理者ダッシュボード</h1>
        
        <section class="mt-4">
            <h2>店舗代表者の追加</h2>
            <form id="representativeForm" class="form-inline" action="{{ route('admin.store.representative') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">代表者名:</label>
                    <input type="text" name="name" id="name" class="form-control mx-2" required>
                </div>
                <div class="form-group">
                    <label for="email">メールアドレス:</label>
                    <input type="email" name="email" id="email" class="form-control mx-2" required>
                </div>
                <div class="form-group">
                    <label for="password">パスワード:</label>
                    <input type="password" name="password" id="password" class="form-control mx-2" required>
                </div>
                <button type="submit" class="btn btn-primary">店舗代表者を追加</button>
            </form>

            <div id="representativeResult" class="mt-3"></div>
        </section>

         <hr class="my-5">
         
        <section class="mt-5">
            <h2>利用者へのお知らせメール送信</h2>
            <form id="notificationForm" class="form-inline" action="{{ route('admin.send.notification') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="subject">件名:</label>
                    <input type="text" name="subject" id="subject" class="form-control mx-2" required>
                </div>
                <div class="form-group">
                    <label for="message">メッセージ内容:</label>
                    <textarea name="message" id="message" class="form-control mx-2" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary send-email-btn">メールを送信</button>
            </form>

            <div id="notificationResult" class="mt-3"></div>
        </section>
    </div>

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
            .then(response => response.json())
            .then(data => {
                let resultDiv = document.getElementById('representativeResult');
                if (data.success) {
                    alert("店舗代表者が追加されました");
                    document.getElementById('representativeForm').reset();
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-danger">エラーが発生しました: ' + data.message + '</div>';
                }
            })
            .catch(error => console.error('Error:', error));
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
            .then(response => response.json())
            .then(data => {
                let resultDiv = document.getElementById('notificationResult');
                if (data.success) {
                    alert("メールを送信しました");
                    document.getElementById('notificationForm').reset();
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-danger">エラーが発生しました: ' + data.message + '</div>';
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const isAuthenticated = sessionStorage.getItem("admin_authenticated");
        
        if (!isAuthenticated) {
            alert("管理者パスコードが必要です");
            window.location.href = "{{ route('login') }}";
        }
    });
    </script>
@endsection
