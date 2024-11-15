<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;

class ReviewController extends Controller
{
    public function create($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);
        return view('review', compact('shop'));
    }

    public function store(ReviewRequest $request, $shop_id)
    {
    $validated = $request->validated();

    Review::create([
        'shop_id' => $shop_id,
        'rating' => $validated['rating'],
        'comment' => $validated['comment'],
        'user_id' => auth()->id(),
        ]);

     return redirect()->route('review.thanks');
    }
}