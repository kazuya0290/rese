<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ShopAllController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RepresentativeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\RegisterController;


Auth::routes(['verify' => true]);

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index'); 
    Route::post('/store-representative', [AdminController::class, 'storeRepresentative'])->name('store.representative');
    Route::post('/send-notification', [AdminController::class, 'sendNotification'])->name('send.notification');
    Route::post('admin.import-csv', [AdminController::class, 'importCsv'])->name('import.csv');
    Route::get('/reviews/all', [AdminController::class, 'getAllReviews'])->name('reviews.all');
});
    Route::delete('/reviews/{id}', [AdminController::class, 'destroy'])->name('admin.reviews.destroy');

Route::get('/representative/login', [RepresentativeController::class, 'showLoginForm'])->name('representative.login');
Route::post('/representative/login', [RepresentativeController::class, 'login']);
Route::get('/representative', [RepresentativeController::class, 'index'])->name('representative.index'); 
Route::post('/representative/logout', [RepresentativeController::class, 'logout'])->name('representative.logout');
Route::get('/representative/reservations', [RepresentativeController::class, 'getReservations'])->name('representative.reservations');


Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');

Route::get('/shop/create', [ShopController::class, 'create'])->name('shop.create'); 
Route::get('/shop/{id}/edit', [ShopController::class, 'edit'])->name('shop.edit'); 
Route::post('/shop', [ShopController::class, 'store'])->name('shop.store');
Route::post('/shop/{id}/update', [ShopController::class, 'update'])->name('shop.update');
Route::post('/shop/{id}/save-image', [ShopController::class, 'saveImage'])->name('shop.saveImage'); 

Route::post('/favorite/{shopId}', [ShopAllController::class, 'toggleFavorite'])->middleware('auth')->name('favorite.toggle');
Route::get('/', [ShopAllController::class, 'index'])->name('shop.index');
Route::get('/shop/{id}', [ShopAllController::class, 'show'])->name('shop.show');

Route::post('/favorites/toggle/{shop}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
Route::post('/favorite/{shop}', [FavoriteController::class, 'store'])->name('favorite.store');
Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

Route::post('/shop/{id}/reserve', [ReservationController::class, 'store'])->name('reservation.store');
Route::resource('reservations', ReservationController::class);
Route::get('/reservation/qr', [ReservationController::class, 'qr'])->name('reservation.qr');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage', [MyPageController::class, 'showMyPage'])->name('my_page');
});


Route::get('/verify', function () {
    return view('auth.verify');
})->name('verify');

Route::get('/register_thanks', function () {
    return view('layouts.register_thanks');
})->name('register.thanks');

Route::get('/done', function () {
    return view('done');
})->name('done.thanks');

Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');

Route::get('/reviews/create/{shop_id}', [ReviewController::class, 'create'])->name('review.create');
Route::post('/reviews/store/{shop_id}', [ReviewController::class, 'store'])->name('review.store');
Route::get('/reviews/{review_id}/edit', [ReviewController::class, 'edit'])->name('review.edit');
Route::put('/reviews/{review_id}/update', [ReviewController::class, 'update'])->name('review.update');
Route::delete('/reviews/{id}/delete', [ReviewController::class, 'destroy'])->name('reviews.destroy');

Route::get('/review_thanks', function () {
    return view('review_thanks');
})->name('review.thanks');

Route::get('storage/{path}', function ($path) {
    return response()->file(storage_path('public/images/' . $path));
});