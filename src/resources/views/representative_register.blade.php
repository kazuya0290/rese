@extends('layouts.app')

@section('content')
<div class="container">
    <h1>店舗代表者の登録</h1>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('admin.representative.store') }}" id="representativeForm" method="POST">
        @csrf
    <input type="text" name="name" placeholder="代表者名" required>
    <input type="email" name="email" placeholder="メールアドレス" required>
    <input type="password" name="password" placeholder="パスワード" required>
    <button type="submit">代表者を追加</button>
    </form>
</div>

<script>
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
            if (data.success) {
                alert('代表者が正常に追加されました');
            } else {
                alert('エラーが発生しました');
            }
        })
        .catch(error => console.error('Error:', error));
    });
</script>
@endsection
