<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;

class MyPageController extends Controller
{
    public function showMyPage()
    {

        $user = Auth::user();
        $reservations = $user->reservations; 
        $favorites = $user->favorites()->pluck('shop_id')->toArray(); 
        $shops = Shop::whereIn('id', $favorites)->get(); 

        return view('my_page', compact('user', 'reservations', 'favorites', 'shops'));
    }

     public function myPage()
    {

        $user = Auth::user();
        $reservations = $user->reservations; 
        $favorites = $user->favorites()->pluck('shop_id')->toArray();         
        $shops = Shop::whereIn('id', $favorites)->get(); 

        return view('my_page', compact('user', 'reservations', 'favorites', 'shops'));
    }

    public function showAllShops()
    {
        $shops = Shop::all(); 
        $favorites = auth()->user() ? auth()->user()->favorites->pluck('id')->toArray() : []; 

        return view('shop_all', compact('shops', 'favorites'));
    }

    
}
