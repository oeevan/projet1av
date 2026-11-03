<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::all();

        return view('home', [
            'games' => $games,
            'featured' => $games->firstWhere('featured', true) ?? $games->first(),
            'categories' => Category::all(),
        ]);
    }

    public function show(int $id)
    {
        return view('game-detail', ['game' => Game::findOrFail($id)]);
    }
}
