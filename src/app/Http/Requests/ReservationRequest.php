<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check(); 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shop_id' => 'required',
            'date' => 'required',
            'time' => 'required', 
            'number_of_people' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'date.required' => 'ご来店の日付を入力してください',
            'time.required' => 'ご来店時間を設定してください',
            'number_of_people.required' => 'ご来店人数を入力してください',
            'number_of_people.integer' => '数値で入力してください',
            'number_of_people.min' => '来店人数は1人以上入力してください'
        ];
    }
    /**
     * カスタムバリデーションを追加する
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $userId = auth()->id();
            $reservationDate = Carbon::parse($this->date)->format('Y-m-d');
            $reservationTime = $this->time; // `time` フィールドから取得
            $shopId = $this->shop_id;

            $currentDate = Carbon::now()->format('Y-m-d');
            if ($reservationDate < $currentDate) {
            $validator->errors()->add('date', '過去の日付で予約することは出来ません');
            }
            
            // 同じ店舗で同じ日時の予約をチェック
            $existingReservationSameShop = Reservation::where('user_id', $userId)
                ->where('shop_id',  $shopId)
                ->whereDate('date', $reservationDate) // カラム名が `date` であることを確認
                ->exists();

            if ($existingReservationSameShop) {
                // 同じ店舗で同日に既に予約がある場合のエラーメッセージ
                $validator->errors()->add('date', '同じ店舗で同日に既に予約が存在します');
            }

            // 別の店舗で同日の予約をチェック
            $existingReservationOtherShop = Reservation::where('user_id', $userId)
                ->where('shop_id', '!=', $shopId)
                ->whereDate('date', $reservationDate)
                ->exists();

            if ($existingReservationOtherShop) {
                // 別の店舗で同日に既に予約がある場合のエラーメッセージ
                $validator->errors()->add('date', '同日に別の店舗で予約が既に存在します');
            }
        });
    }
}


