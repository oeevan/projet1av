<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Models\Category;
use App\Models\Game;
use App\Models\Platform;
use App\Services\GameService;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // GameService est injecté par le conteneur de Laravel : jamais de new
    public function __construct(private GameService $games) {}

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

    public function store(StoreGameRequest $request)
    {
        $game = $this->games->create($request->validated());

        return redirect()->route('games.show', $game)->with('success', 'Jeu créé !');
    }

    public function edit(Game $game)
    {
        return view('games.edit', [
            // load('platforms') : preventLazyLoading interdit le chargement paresseux dans la vue
            'game' => $game->load('platforms'),
            'categories' => Category::all(),
            'platforms' => Platform::all(),
        ]);
    }

    public function update(UpdateGameRequest $request, Game $game)
    {
        $this->games->update($game, $request->validated());

        return redirect()->route('games.show', $game)->with('success', 'Jeu modifié !');
    }

    public function destroy(Game $game)
    {
        $this->games->delete($game);

        return redirect()->route('home')->with('success', 'Jeu supprimé !');
    }
}
