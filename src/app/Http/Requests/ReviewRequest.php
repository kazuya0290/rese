<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
             'comment' => 'required|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '星にカーソルを合わせてクリックし、評価してください',
            'comment.required' => 'コメントを入力してください',
            'comment.string' => 'コメントは文字列で入力してください',
            'comment.max' => 'コメントは500文字以内で入力してください',
        ];
    }
}