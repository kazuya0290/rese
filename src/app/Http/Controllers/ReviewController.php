<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth; 

class ReviewController extends Controller
{
    public function create($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);
        $favorites = Auth::user() ? Auth::user()->favorites->pluck('shop_id')->toArray() : [];
        $shops = Shop::whereIn('id', $favorites)->get(); 
        $review = null;

        return view('review', compact('shop', 'favorites', 'shops', 'review'));
    }

    public function store(ReviewRequest $request, $shop_id)
    {
        $validated = $request->validated();

        $userId = auth()->id();

        $existingReviewsCount = Review::where('shop_id', $shop_id)
            ->where('user_id', $userId)
            ->count();

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews', 'public');
        }
        Review::create([
            'shop_id' => $shop_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'user_id' => auth()->id(),
            'image' => $imagePath,
        ]);

        return redirect()->route('review.thanks');
    }

     public function edit($id)
    {
        
        $review = Review::findOrFail($id);
        $shop = $review->shop;

        if (Gate::denies('update-review', $review)) {
            abort(403, 'この口コミを編集する権限がありません。');
        }

        $imageUrl = $review->image ? Storage::url($review->image) : null;

        $favorites = Auth::user() ? Auth::user()->favorites->pluck('shop_id')->toArray() : [];

        $shops = Shop::whereIn('id', $favorites)->get(); 

        return view('review', compact('review', 'shop', 'imageUrl', 'favorites', 'shops'));
    }


    public function update(ReviewRequest $request, $id)
    {
    $review = Review::findOrFail($id);
    
    $review->update($request->all());

    if (Gate::denies('update-review', $review)) {
        abort(403, 'この口コミを編集する権限がありません。');
    }

    $validatedData = $request->validated();

    $review->rating = $validatedData['rating'];
    $review->comment = $validatedData['comment'];

    if ($request->hasFile('image')) {
        if ($review->image) {
            Storage::disk('public')->delete($review->image);
        }

        $path = $request->file('image')->store('reviews', 'public');
        $review->image = $path;
    }

    $review->save();

   return redirect()->route('shop.show', $review->shop_id)
                     ->with('success', '口コミの更新が完了しました');
    }


    public function destroy($id)
    {
    $review = Review::findOrFail($id);
    
    if (auth()->id() !== $review->user_id) {
        return redirect()->back()->with('error', '削除する権限がありません');
    }
    
    if ($review->image) {
        Storage::delete('public/' . $review->image);
    }
    
    $review->delete();
    
    return redirect()->back()->with('success', '口コミを削除しました');
    }
}
