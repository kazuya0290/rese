<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Genre;
use Illuminate\Http\Request;

class Shop_allController extends Controller
{
    public function index()
    {
        
        $areas = Area::all();

       
        $genres = Genre::all();

        
        return view('shop_all', compact('areas', 'genres'));
    }
}
