<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_lists_games_and_featured(): void
    {
        Game::factory()->featured()->create(['title' => 'Hollow Knight']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Hollow Knight')
            ->assertSee('A la une');
    }

    public function test_category_filter(): void
    {
        [$rpg, $action] = Category::factory()->count(2)->create();
        Game::factory()->for($rpg)->create(['title' => 'Jeu RPG']);
        Game::factory()->for($action)->create(['title' => 'Jeu Action']);

        $this->get("/?category={$rpg->id}")
            ->assertOk()
            ->assertViewHas('games', fn ($games) => $games->pluck('title')->all() === ['Jeu RPG']);
    }

    public function test_search_combined_with_category(): void
    {
        $rpg = Category::factory()->create();
        Game::factory()->for($rpg)->create(['title' => 'Zelda Breath']);
        Game::factory()->for($rpg)->create(['title' => 'Mario Kart']);
        Game::factory()->create(['title' => 'Zelda Autre']);

        $this->get("/?search=zelda&category={$rpg->id}")
            ->assertOk()
            ->assertViewHas('games', fn ($games) => $games->pluck('title')->all() === ['Zelda Breath']);
    }

    public function test_pagination_keeps_query_string(): void
    {
        Game::factory()->count(12)->create(['title' => fn () => 'Jeu '.fake()->unique()->word()]);

        $this->get('/?search=Jeu')
            ->assertOk()
            ->assertViewHas('games', fn ($games) => $games->count() === 9 && $games->total() === 12)
            ->assertSee('search=Jeu&amp;page=2', false);
    }

    public function test_game_detail_by_slug_and_404(): void
    {
        $game = Game::factory()->create(['title' => 'Elden Ring', 'slug' => 'elden-ring']);

        $this->get('/game/elden-ring')->assertOk()->assertSee($game->title);
        $this->get('/game/inconnu')->assertNotFound()->assertSee('Retourner au catalogue');
        $this->get('/url-inexistante')->assertNotFound();
    }
}
