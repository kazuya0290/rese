<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopAllController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Auth;

// 店舗一覧・詳細ページのルート
Route::get('/', [ShopAllController::class, 'index']);
Route::get('/shops', [ShopAllController::class, 'index'])->name('shop.index');
Route::get('/shop/{id}', [ShopAllController::class, 'show'])->name('shop.show');

// お気に入り機能のルート (ログインが必要)
Route::post('/favorite/{shopId}', [ShopAllController::class, 'toggleFavorite'])->middleware('auth');

Route::post('/shop/{id}/reserve', [ReservationController::class, 'store'])->name('reservation.store');

// 認証関連のルート
Auth::routes();

