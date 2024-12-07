<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use App\Models\Representative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;

class ShopAllController extends Controller
{
    public function index(Request $request)
    {
        $areas = Area::all();
        $genres = Genre::all();
        $representative = Auth::guard('representative')->user(); 
        $query = Shop::query()->withAvg('reviews', 'rating'); 

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

        if ($request->has('sort')) {
                $sort = $request->sort;
            if ($sort === 'high_rating') {
        
                $query->orderByRaw('ISNULL(reviews_avg_rating), reviews_avg_rating DESC');
            } elseif ($sort === 'rating_low') {
                $query->orderByRaw('ISNULL(reviews_avg_rating), reviews_avg_rating ASC');
            } elseif ($sort === 'random') {
                $query->inRandomOrder();
            }
        }

        $shops = $query->get();
        $favorites = auth()->check() ? auth()->user()->favorites->pluck('id')->toArray() : []; 

        return view('shop_all', compact('shops', 'areas', 'genres', 'favorites', 'representative'));
    }

    public function show($id)
    {
        $shop = Shop::with('area', 'genre')->findOrFail($id);

        return view('shop_detail', compact('shop'));
    }

    public function toggleFavorite(Request $request, $shopId)
    {
        $user = Auth::user();

        $shop = Shop::find($shopId);
        if (!$shop) {
            return response()->json(['success' => false, 'message' => 'ショップが見つかりません。']);
        }

        $isFavorite = $user->favorites()->where('shop_id', $shopId)->exists();

        if ($request->is_favorite && !$isFavorite) {
            $user->favorites()->attach($shopId);
        } elseif (!$request->is_favorite && $isFavorite) {
            $user->favorites()->detach($shopId);
        }

        return response()->json(['success' => true]);
    }
}
