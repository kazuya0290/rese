<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // 店舗の詳細を表示するメソッド
    public function show($id)
    {
        // 指定されたIDの店舗を取得
        $shop = Shop::findOrFail($id);

        // 店舗詳細画面を返す
        return view('shops.show', compact('shop'));
    }

    // お気に入りの登録/解除をトグルするメソッド
    public function toggleFavorite($shopId)
    {
        $user = auth()->user();
        $shop = Shop::findOrFail($shopId);

        if ($user->favorites()->where('shop_id', $shopId)->exists()) {
            // 既にお気に入り登録されていれば削除
            $user->favorites()->detach($shopId);
        } else {
            // お気に入り登録されていなければ追加
            $user->favorites()->attach($shopId);
        }

        return back();
    }
}
