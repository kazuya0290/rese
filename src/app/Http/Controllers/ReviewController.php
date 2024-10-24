<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Shop;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);
        return view('review', compact('shop'));
    }

    public function store(Request $request, $shop_id)
    {
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|max:500',
    ]);

    Review::create([
        'shop_id' => $shop_id,
        'rating' => $request->rating,
        'comment' => $request->comment,
        'user_id' => auth()->id(),
    ]);

     return redirect()->route('review.thanks');
    }

}
