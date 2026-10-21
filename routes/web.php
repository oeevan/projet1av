<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GameController::class, 'index'])->name('home');

Route::get('/hello', function () {
    return 'Hello WishFlix !';
});

Route::get('/hello/{name}', function (string $name) {
    return "Hello $name !";
});