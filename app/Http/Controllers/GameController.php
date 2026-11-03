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

    // Bonus 1 : route model binding, Laravel trouve le jeu par son slug (404 sinon)
    public function show(Game $game)
    {
        return view('game-detail', ['game' => $game]);
    }
}
