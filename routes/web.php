<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GameController::class, 'index'])->name('home');

Route::get('/game/{game:slug}', [GameController::class, 'show'])
    ->name('games.show');

Route::get('/games/create', [GameController::class, 'create'])->name('games.create');
Route::post('/games', [GameController::class, 'store'])->name('games.store');

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
