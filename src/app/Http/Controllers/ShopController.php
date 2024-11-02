<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // 店舗の詳細を表示するメソッド
    public function show($id)
    {
        // 指定されたIDの店舗を取得し、口コミを含めて取得
        $shop = Shop::with('reviews.user')->findOrFail($id);

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
            'image' => 'required|image|mimes:jpg,jpeg,png', // 2MBまでの画像
        ]);

        $shop = Shop::findOrFail($id);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('shops', 'public'); // ストレージのshopsフォルダに保存
            $shop->image_path = $path;
            $shop->save();
        }

        return redirect()->route('shop.show', $id)->with('success', '画像が保存されました。');
    }

    public function edit($id)
    {
        $shop = Shop::findOrFail($id);
        
        // ここで地域情報を取得
        $areas = Area::all(); // Areaモデルを使用して地域情報を取得する場合
        $genres = Genre::all();

        return view('shop_edit', compact('shop', 'areas', 'genres')); // shop と areas をビューに渡す
    }

   public function update(Request $request)
    {
    // バリデーション
    $request->validate([
        'name' => 'required|string|max:255',
        'area_id' => 'required|exists:areas,id',
        'genre_id' => 'required|exists:genres,id',
        'description' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif', // 画像のバリデーション
    ]);

    // IDをリクエストから取得
    $shop = Shop::findOrFail($request->id); 

    // ショップの情報を更新
    $shop->name = $request->name;
    $shop->area_id = $request->area_id;
    $shop->genre_id = $request->genre_id;
    $shop->description = $request->description; // outlineをdescriptionに修正

    // 画像がアップロードされた場合
    if ($request->hasFile('image')) {

        $shop->image = $request->file('image')->store('images', 'public'); // 適切なストレージに保存
    }

    $shop->save(); // 更新を保存

    return redirect()->back()->with('success', '店舗情報が更新されました。');
    }

   public function create()
    {
    $areas = Area::all();
    $genres = Genre::all();
    
    return view('shop_create', compact('areas', 'genres'));
    }

    public function store(Request $request)
{
    // バリデーション
    $request->validate([
        'name' => 'required|string|max:255',
        'area_id' => 'required|exists:areas,id',
        'genre_id' => 'required|exists:genres,id',
        'description' => 'required|string|max:1000', // 説明をnullableに設定
        'image' => 'required|image|mimes:jpeg,png,jpg,gif', // 画像のバリデーション
    ]);

    $shop = new Shop();
    $shop->name = $request->input('name');
    $shop->area_id = $request->input('area_id');
    $shop->genre_id = $request->input('genre_id');
    $shop->description = $request->input('description'); 
    if ($request->hasFile('image')) {
    $path = $request->file('image')->store('public/images');
    $shop->image = str_replace('public/', '', $path);
    }

    $shop->save();

  return redirect()->back()->with('success', '店舗が追加されました');
}

}

