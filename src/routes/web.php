<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shop_allController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\GenreController;
use Illuminate\Support\Facades\Auth;




Route::get('/', [Shop_allController::class, 'index']);
Route::get('/shop_all', [AreaController::class, 'index'])->name('shop_all');
Route::get('/shop_all', [Shop_allController::class, 'index'])->name('shop_all');
Route::get('/shop_all', [GenreController::class, 'index'])->name('shop_all');
Auth::routes();

