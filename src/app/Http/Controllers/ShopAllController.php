<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Authファサードの追加

class ShopAllController extends Controller
{
    public function index(Request $request)
{
    // エリアとジャンルの全データを取得
    $areas = Area::all();
    $genres = Genre::all();

    // ショップのクエリを作成
    $query = Shop::query();

    // エリアが指定されている場合の絞り込み
    if ($request->has('area') && $request->area != 'All_area') {
        $query->where('area_id', $request->area);
    }

    // ジャンルが指定されている場合の絞り込み
    if ($request->has('genre') && $request->genre != 'All_genre') {
        $query->where('genre_id', $request->genre);
    }

    // キーワード検索
    if ($request->filled('keyword')) {
        $keyword = $request->keyword;
        $query->where(function ($q) use ($keyword) {
            $q->where('name', 'LIKE', "%{$keyword}%")
              ->orWhereHas('area', function ($query) use ($keyword) {
                  $query->where('area', 'LIKE', "%{$keyword}%");
              })
              ->orWhereHas('genre', function ($query) use ($keyword) {
                  $query->where('genre', 'LIKE', "%{$keyword}%");
              });
        });
    }

    // 最終的な結果を取得
    $shops = $query->get();

    // ユーザーがログインしている場合のみお気に入りを取得
    $favorites = auth()->check() ? auth()->user()->favorites->pluck('id')->toArray() : []; 

    // ビューへ渡す変数
    return view('shop_all', compact('shops', 'areas', 'genres', 'favorites'));
}


    public function show($id)
    {
        // ショップの詳細情報を取得
        $shop = Shop::with('area', 'genre')->findOrFail($id);

        return view('shop_detail', compact('shop'));
    }

    public function toggleFavorite(Request $request, $shopId)
    {
        // 認証ユーザーを取得
        $user = Auth::user();

        // ショップが存在するか確認
        $shop = Shop::find($shopId);
        if (!$shop) {
            return response()->json(['success' => false, 'message' => 'ショップが見つかりません。']);
        }

        // 既にお気に入りかどうかを確認
        $isFavorite = $user->favorites()->where('shop_id', $shopId)->exists();

        // お気に入りに追加または削除
        if ($request->is_favorite && !$isFavorite) {
            $user->favorites()->attach($shopId);
        } elseif (!$request->is_favorite && $isFavorite) {
            $user->favorites()->detach($shopId);
        }

        return response()->json(['success' => true]);
    }
}
