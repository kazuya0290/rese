<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:191|unique:representatives,name',
            'email' => 'required|email|max:191|unique:representatives,email',
            'password' => 'required|min:8|max:191',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前を入力してください',
            'name.string' => '名前は文字列で入力してください',
            'name.max' => '名前は191文字以内で入力してください',
            'name.unique' => 'この代表者は登録済みです',
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスは「ユーザー名@ドメイン」形式で入力してください',
            'email.max' => 'メールアドレスは191文字以内で入力してください',
            'email.unique' => 'このメールアドレスは登録済みです',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'password.max' => 'パスワードは191文字以内で入力してください'
        ];
    }
}
