@extends('layouts.app')

@section('css') 
<link rel="stylesheet" href="{{ asset('css/admin.css') }}"> 
@endsection

@section('content')
    <div class="container mt-5">
        <h1>管理者画面</h1>
        
        <section class="mt-4">
            <h2>店舗代表者の追加</h2>
            <form id="representativeForm" class="form-inline" action="{{ route('admin.store.representative') }}" method="POST">
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
            <form id="notificationForm" class="form-inline" action="{{ route('admin.send.notification') }}" method="POST">
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
            alert("管理者パスコードが必要です.");
            window.location.href = "{{ route('login') }}";
        }
});

</script>
@endsection
