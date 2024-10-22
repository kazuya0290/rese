<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopAllController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Auth;

// 認証関連のルート
Auth::routes(['verify' => true]);

// 店舗一覧・詳細ページのルート
Route::get('/', [ShopAllController::class, 'index'])->name('shop.index');
Route::get('/shop/{id}', [ShopAllController::class, 'show'])->name('shop.show');
Route::post('/shop/{id}/save-image', [ShopController::class, 'saveImage'])->name('shop.saveImage');

// お気に入り機能のルート (ログインが必要)
Route::post('/favorite/{shopId}', [ShopAllController::class, 'toggleFavorite'])->middleware('auth')->name('favorite.toggle');
Route::post('/favorites/toggle/{shop}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
Route::post('/favorite/{shop}', [FavoriteController::class, 'store'])->name('favorite.store');
Route::delete('/favorites/{id}', [FavoritesController::class, 'destroy'])->name('favorites.destroy');
// 予約関連のルート
Route::post('/shop/{id}/reserve', [ReservationController::class, 'store'])->name('reservation.store');
Route::put('/reservations/{id}', [ReservationController::class, 'update'])->name('reservations.update');

// マイページ (認証・メール確認済みが必要)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [MyPageController::class, 'showMyPage'])->name('my_page');
});

// 予約リソースルート
Route::resource('reservations', ReservationController::class);

// メール認証後のリダイレクト先 (サンクスページ)
Route::get('/verify', function () {
    return view('auth.verify');
})->name('verify');

// 登録サンクスページ
Route::get('/register_thanks', function () {
    return view('layouts.register_thanks');
})->name('register.thanks');

// 予約完了サンクスページ
Route::get('/reservation_thanks', function () {
    return view('reservation_thanks');
})->name('reservation.thanks');

Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'checkout'])->name('checkout');

Route::get('/reservation/qr', [ReservationController::class, 'qr'])->name('reservation.qr');


