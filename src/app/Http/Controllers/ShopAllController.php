<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopAllController extends Controller
{
    public function index(Request $request)
    {
        $areas = Area::all();
        $genres = Genre::all();
        $query = Shop::query();

        if ($request->has('area') && $request->area != 'All_area') {
            $query->where('area_id', $request->area);
        }

        if ($request->has('genre') && $request->genre != 'All_genre') {
            $query->where('genre_id', $request->genre);
        }

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

        $shops = $query->get();
        $areas = Area::all();
        $genres = Genre::all();

        return view('shop_all', compact('shops', 'areas', 'genres'));
    }

   public function show($id)
{
    // Eager Loadingでareaとgenreを取得
    $shop = Shop::with('area', 'genre')->findOrFail($id);

    return view('shop_detail', compact('shop'));
}

    public function toggleFavorite(Request $request, $shopId)
    {
        $user = auth()->user(); 
        $isFavorite = $request->input('is_favorite');

        if ($isFavorite) {
            Favorite::create([
                'user_id' => $user->id,
                'shop_id' => $shopId,
            ]);
        } else {
            Favorite::where('user_id', $user->id)
                    ->where('shop_id', $shopId)
                    ->delete();
        }

        return response()->json(['success' => true]);
    }
}
