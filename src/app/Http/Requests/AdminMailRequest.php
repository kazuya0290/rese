<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminMailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'subject' => 'required|max:191',
            'message' => 'required|max:500',
        ];
    }

    public function messages()
    {
        return [
            'subject.required' => '件名を入力してください',
            'subject.max' => '件名は191文字以内で入力してください',
            'message.required' => 'メッセージ内容を入力してください',
            'message.max' => 'メッセージ内容は500文字以内で入力してください'
        ];
    }
}