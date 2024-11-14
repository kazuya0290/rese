<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>予約リマインダー</title>
</head>
<body>
    <p>本日は{{ $reservation->shop->name }} の予約日です！お忘れなく。</p>
    <p>予約日時: {{ \Carbon\Carbon::parse($reservation->date . ' ' . $reservation->time)->format('Y年m月d日 H:i') }}</p>
    <p>人数: {{ $reservation->number_of_people }} 人</p>
    <p>来店後、コメントと５段階評価をお願いいたします</p>
    <p><a href="{{ route('review.create', ['shop_id' => $reservation->shop->id]) }}">レビューする</a></p>
</body>
</html>
