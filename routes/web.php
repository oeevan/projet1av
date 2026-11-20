<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PlatformController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GameController::class, 'index'])->name('home');

Route::get('/game/{game:slug}', [GameController::class, 'show'])
    ->name('games.show');

// Back-office : create, store, edit, update, destroy (games.show existe déjà, par slug)
Route::resource('games', GameController::class)->except(['index', 'show']);
Route::resource('categories', CategoryController::class)->except(['show']);
Route::resource('platforms', PlatformController::class)->except(['show']);

Route::get('/wishlist', function () {
    return view('wishlist');
})->name('wishlist');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/hello', function () {
    return 'Hello WishFlix !';
});

Route::get('/hello/{name}', function (string $name) {
    return "Hello $name !";
});
