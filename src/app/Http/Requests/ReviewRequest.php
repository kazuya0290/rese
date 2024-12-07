<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Review;

class ReviewRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
             'rating' => 'required',
             'image' => 'required|image|mimes:jpeg,png',
             'comment' => [
                        'required',
                        'max:400',
                function ($attribute, $value, $fail) {
                    $shopId = $this->route('shop_id');
                    $userId = auth()->id();

                    $reviewCount = Review::where('shop_id', $shopId)
                        ->where('user_id', $userId)
                        ->count();

                    if ($reviewCount >= 1) {
                    $fail('1店舗に対して2件以上の口コミを追加することはできません');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '星にカーソルを合わせてクリックし、評価してください',
            'comment.required' => 'コメントを入力してください',
            'comment.max' => 'コメントは400文字以内で入力してください',
            'image.required' => '画像を選択してください',
            'image.image' => '画像ファイルを選択してください。',
            'image.mimes' => '選択可能な画像は1MB未満のjpeg,pngのみです',
        ];
    }
}