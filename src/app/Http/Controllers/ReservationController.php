<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        // バリデーションを行う
        $request->validate([
            'reservation_date' => 'required|date',
            'reservation_time' => 'required|date_format:H:i',
            'number_of_people' => 'required|integer|min:1|max:20',
            'shop_id' => 'required|exists:shops,id',
        ]);

        // 予約を作成する
        Reservation::create([
            'user_id' => auth()->id(),
            'shop_id' => $request->input('shop_id'),
            'reservation_date' => $request->input('reservation_date'),
            'reservation_time' => $request->input('reservation_time'),
            'number_of_people' => $request->input('number_of_people'),
        ]);

        // リダイレクトまたは確認メッセージを表示
        return redirect()->back()->with('success', '予約が完了しました！');
    }
}
