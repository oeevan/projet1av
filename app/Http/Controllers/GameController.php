<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(Request $request)
    {
        // Catalogue : filtres optionnels (when), eager loading (with), pagination
        $games = Game::query()
            ->with('category')
            ->when($request->query('search'), fn ($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->when($request->query('category'), fn ($query, $id) => $query->where('category_id', $id))
            ->orderBy('title')
            ->paginate(9)
            ->withQueryString();

        return view('home', [
            'games' => $games,
            'categories' => Category::withCount('games')->orderBy('name')->get(),
            'featured' => Game::with(['category', 'platforms'])->where('featured', true)->inRandomOrder()->first(),
            'topRated' => Game::with('category')->orderByDesc('rating')->limit(6)->get(),
            'bestCategories' => Category::withAvg('games', 'rating')->orderByDesc('games_avg_rating')->get(),
        ]);
    }

    // Route model binding : Laravel trouve le jeu par son slug (404 sinon)
    public function show(Game $game)
    {
        return view('game-detail', ['game' => $game->load(['category', 'platforms'])]);
    }
}
