<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Game;
use App\Models\Platform;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameFormTest extends TestCase
{
    use RefreshDatabase;

    private function validData(array $overrides = []): array
    {
        return [
            'title' => 'Hollow Knight',
            'synopsis' => 'Un metroidvania dans un royaume d\'insectes.',
            'release_year' => 2017,
            'rating' => 9.0,
            'playtime_hours' => 40,
            'cover_url' => 'https://via.assets.so/game.png?id=50',
            'status' => 'released',
            'category_id' => Category::factory()->create()->id,
            'platform_ids' => [Platform::create(['name' => 'PC'])->id, Platform::create(['name' => 'PS5'])->id],
            'featured' => '0',
            ...$overrides,
        ];
    }

    public function test_create_form_is_displayed(): void
    {
        $this->get('/games/create')->assertOk()->assertSee('Nouveau jeu');
    }

    public function test_valid_game_is_created_with_platforms(): void
    {
        $response = $this->post('/games', $this->validData());

        $game = Game::where('slug', 'hollow-knight')->firstOrFail();
        $response->assertRedirect(route('games.show', $game))->assertSessionHas('success', 'Jeu créé !');
        $this->assertCount(2, $game->platforms);
        $this->assertFalse($game->featured);
    }

    public function test_invalid_data_is_rejected_and_input_kept(): void
    {
        $this->from('/games/create')
            ->post('/games', $this->validData(['title' => '', 'category_id' => 999, 'status' => 'nope']))
            ->assertRedirect('/games/create')
            ->assertSessionHasErrors(['title', 'category_id', 'status'])
            ->assertSessionHasInput('synopsis');

        $this->assertDatabaseCount('games', 0);
    }

    public function test_same_title_gets_a_unique_slug(): void
    {
        $this->post('/games', $this->validData());
        $this->post('/games', $this->validData());

        $this->assertEqualsCanonicalizing(['hollow-knight', 'hollow-knight-2'], Game::pluck('slug')->all());
    }

    public function test_edit_form_is_prefilled(): void
    {
        $game = Game::factory()->create(['title' => 'Celeste']);

        $this->get("/games/{$game->id}/edit")
            ->assertOk()
            ->assertSee('Modifier le jeu')
            ->assertSee('value="Celeste"', false);
    }

    public function test_game_is_updated_and_keeps_its_slug(): void
    {
        $game = Game::factory()->create(['title' => 'Ancien', 'slug' => 'ancien']);

        $this->put("/games/{$game->id}", $this->validData(['title' => 'Nouveau titre']))
            ->assertRedirect(route('games.show', 'ancien'))
            ->assertSessionHas('success', 'Jeu modifié !');

        $this->assertSame('Nouveau titre', $game->fresh()->title);
        $this->assertCount(2, $game->fresh()->platforms);
    }

    public function test_invalid_update_is_rejected(): void
    {
        $game = Game::factory()->create(['title' => 'Intact']);

        $this->from("/games/{$game->id}/edit")
            ->put("/games/{$game->id}", $this->validData(['title' => '']))
            ->assertRedirect("/games/{$game->id}/edit")
            ->assertSessionHasErrors('title');

        $this->assertSame('Intact', $game->fresh()->title);
    }

    public function test_game_with_platforms_is_deleted(): void
    {
        $game = Game::factory()->create();
        $game->platforms()->attach(Platform::create(['name' => 'PC']));

        $this->delete("/games/{$game->id}")
            ->assertRedirect(route('home'))
            ->assertSessionHas('success', 'Jeu supprimé !');

        $this->assertModelMissing($game);
        $this->assertDatabaseCount('game_platform', 0);
    }

    public function test_unknown_game_returns_404(): void
    {
        $this->get('/games/999/edit')->assertNotFound();
        $this->put('/games/999', $this->validData())->assertNotFound();
        $this->delete('/games/999')->assertNotFound();
    }
}
