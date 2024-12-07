<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

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

    if (Gate::denies('update-review', $review)) {
        abort(403, 'この口コミを編集する権限がありません。');
        }

    $shop = $review->shop;
    
    $imageUrl = $review->image ? Storage::url($review->image) : null;

    return view('review', compact('review', 'shop', 'imageUrl'));
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
    $review->delete();

    return redirect()->route('shop.show', $review->shop_id)->with('success', '口コミを削除しました。');
    }

}
