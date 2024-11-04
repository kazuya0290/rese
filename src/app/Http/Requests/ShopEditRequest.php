<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:191',
                'unique:shops,name,' . $this->route('id'),
            ],
            'description' => 'required|string|max:191',
            'image' => 'image|mimes:jpeg,png,jpg,gif',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前を入力してください',
            'name.string' => '名前は文字列で入力してください',
            'name.max' => '名前は191文字以内で入力してください',
            'name.unique' => 'この店舗名は登録済みです',
            'description.required' => '説明を入力してください',
            'description.string' => '説明は文字列で入力してください',
            'description.max' => '説明は191文字以内で入力してください',
            'image.image' => '画像ファイルを選択してください。',
            'image.mimes' => '選択可能な画像はjpeg,png,jpg,gifのみです',
        ];
    }
}
