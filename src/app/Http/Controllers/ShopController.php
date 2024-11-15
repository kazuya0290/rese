<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Requests\ShopEditRequest;
use App\Http\Requests\ShopCreateRequest;

class ShopController extends Controller
{
   
    public function show($id)
    {
       
        $shop = Shop::with('reviews.user')->findOrFail($id);

        
        return view('shops.show', compact('shop'));
    }

    public function toggleFavorite($shopId)
    {
        $user = auth()->user();
        $shop = Shop::findOrFail($shopId);

        if ($user->favorites()->where('shop_id', $shopId)->exists()) {
           
            $user->favorites()->detach($shopId);
        } else {
           
            $user->favorites()->attach($shopId);
        }

        return back();
    }

    public function showAllShops()
    {

        $shops = Shop::all();
        $favorites = auth()->check() ? auth()->user()->favorites->pluck('id')->toArray() : []; 

        return view('shop_all', compact('shops', 'favorites'));
    }

    public function saveImage(Request $request, $id)
    {
        
        $shop = Shop::findOrFail($id);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('shops', 'public'); 
            $shop->save();
        }

        return redirect()->route('shop.show', $id)->with('success', '画像が保存されました。');
    }

    public function edit($id)
    {

        $shop = Shop::findOrFail($id);
        
        $areas = Area::all(); 
        $genres = Genre::all();

        return view('shop_edit', compact('shop', 'areas', 'genres')); 
    }

    public function update(ShopEditRequest $request)
    {
    $validated = $request->validated();
    
    $shop = Shop::findOrFail($request->id); 

    $shop->name = $validated['name'];
    $shop->area_id = $request->area_id;
    $shop->genre_id = $request->genre_id;
    $shop->description = $validated['description'];

    
    if ($request->hasFile('image')) {
        $shop->image = $request->file('image')->store('images', 'public'); 
        }

    $shop->save(); 

    return redirect()->back()->with('success', '店舗情報が更新されました。');
    }


    public function create()
    {
    $areas = Area::all();
    $genres = Genre::all();
    
    return view('shop_create', compact('areas', 'genres'));
    }

    public function store(ShopCreateRequest $request)
    {
    
    $validated = $request->validated();

    $shop = new Shop();
    $shop->name =$validated['name'];
    $shop->area_id = $validated['area_id']; 
    $shop->genre_id = $validated['genre_id'];
    $shop->description = $validated['description'];
    if ($request->hasFile('image')) {
    $path = $request->file('image')->store('public/images');
    $shop->image = str_replace('public/', '', $path);
        }

    $shop->save();

    return redirect()->back()->with('success', '店舗が追加されました');
    }
}