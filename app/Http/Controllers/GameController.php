<?php

namespace App\Http\Controllers;

use App\Enums\GameStatus;
use App\Models\Category;
use App\Models\Game;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

    public function create()
    {
        return view('games.create', [
            'categories' => Category::all(),
            'platforms' => Platform::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'synopsis' => ['required', 'string'],
            'release_year' => ['required', 'integer', 'between:1970,2100'],
            'rating' => ['required', 'numeric', 'between:0,10'],
            'playtime_hours' => ['required', 'integer', 'min:0'],
            'cover_url' => ['required', 'url'],
            'status' => ['required', Rule::enum(GameStatus::class)],
            'category_id' => ['required', 'exists:categories,id'],
            'platform_ids' => ['nullable', 'array'],
            'platform_ids.*' => ['integer', 'exists:platforms,id'],
            'featured' => ['boolean'],
        ]);

        $game = Game::create([
            ...Arr::except($data, 'platform_ids'),
            'slug' => $this->uniqueSlug($data['title']),
        ]);
        $game->platforms()->sync($data['platform_ids'] ?? []);

        return redirect()->route('games.show', $game)->with('success', 'Jeu créé !');
    }

    // La colonne slug est unique (bonus S3) : « Zelda » puis « Zelda » → zelda, zelda-2
    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;

        for ($i = 2; Game::where('slug', $slug)->exists(); $i++) {
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }
}
