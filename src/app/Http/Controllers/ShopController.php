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

        $shop = Shop::with('reviews.user')->find($id); // 口コミを含めて取得
        return view('shop_detail', compact('shop'));
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

     public function showAllShops()
    {
        $shops = Shop::all(); // 全ての店舗を取得

        // ユーザーがログインしている場合のみお気に入りを取得
        $favorites = auth()->check() ? auth()->user()->favorites->pluck('id')->toArray() : []; 

        // ビューにデータを渡す
        return view('shop_all', compact('shops', 'favorites'));
    }

    public function saveImage(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048', // 2MBまでの画像
        ]);

        $shop = Shop::findOrFail($id);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('shops', 'public'); // ストレージのshopsフォルダに保存
            $shop->image_path = $path; // 画像のパスをモデルに保存（image_pathは適宜カラム名を変更）
            $shop->save();
        }

        return redirect()->route('shop.detail', $id)->with('success', '画像が保存されました。');
    }
}
