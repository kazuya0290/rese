<?php

use App\Models\Favorite;
use App\Models\Shop;

class FavoriteController extends Controller
{
     public function store(Shop $shop)
    {
        $user = Auth::user();

        // お気に入りに追加
        if (!$user->favorites()->where('shop_id', $shop->id)->exists()) {
            $user->favorites()->attach($shop->id);
        }

        return back()->with('success', 'お気に入りに追加されました！');
    }
    
    public function toggle($shopId)
    {
        $user = auth()->user();
        $shop = Shop::findOrFail($shopId);

        // お気に入りの追加または削除
        if ($user->favorites()->where('shop_id', $shopId)->exists()) {
            $user->favorites()->detach($shopId);
        } else {
            $user->favorites()->attach($shopId);
        }

        return redirect()->back();
    }
    
    public function destroy($id)
    {
    // お気に入りの店舗を削除するロジックをここに記述
    // 例えば、Userのfavoritesリレーションを利用して削除することができます。

    $user = Auth::user();
    $user->favorites()->detach($id); // ここでお気に入りを削除

    return redirect()->back()->with('success', 'お気に入りを削除しました。');
    }
}
