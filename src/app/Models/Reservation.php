<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    // テーブル名を指定（もしテーブル名が異なる場合）
    protected $table = 'reservations';

    // 一括割り当て可能なフィールドの指定
    protected $fillable = [
        'shop_id', 'user_id', 'date', 'time', 'number_of_people'
    ];

    // 店舗とのリレーション (予約は1つの店舗に属する)
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // ユーザーとのリレーション (予約は1人のユーザーに属する)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
