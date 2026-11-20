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
}
