<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopAllController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Auth;

Auth::routes(['verify' => true]);


Route::get('/', [ShopAllController::class, 'index'])->name('shop.index');
Route::get('/shop/{id}', [ShopAllController::class, 'show'])->name('shop.show');
Route::post('/shop/{id}/save-image', [ShopController::class, 'saveImage'])->name('shop.saveImage');


Route::post('/favorite/{shopId}', [ShopAllController::class, 'toggleFavorite'])->middleware('auth')->name('favorite.toggle');

Route::post('/favorites/toggle/{shop}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

Route::post('/favorite/{shop}', [FavoriteController::class, 'store'])->name('favorite.store');
Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

Route::post('/shop/{id}/reserve', [ReservationController::class, 'store'])->name('reservation.store');
Route::put('/reservations/{id}', [ReservationController::class, 'update'])->name('reservations.update');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [MyPageController::class, 'showMyPage'])->name('my_page');
});


Route::resource('reservations', ReservationController::class);


Route::get('/verify', function () {
    return view('auth.verify');
})->name('verify');

Route::get('/register_thanks', function () {
    return view('layouts.register_thanks');
})->name('register.thanks');


Route::get('/reservation_thanks', function () {
    return view('reservation_thanks');
})->name('reservation.thanks');

Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'checkout'])->name('checkout');

Route::get('/reservation/qr', [ReservationController::class, 'qr'])->name('reservation.qr');

Route::get('/reviews/create/{shop_id}', [ReviewController::class, 'create'])->name('review.create');

Route::post('/reviews/store/{shop_id}', [ReviewController::class, 'store'])->name('review.store');

Route::get('/review_thanks', function () {
    return view('review_thanks');
})->name('review.thanks');

