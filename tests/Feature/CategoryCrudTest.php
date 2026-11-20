<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_lists_categories_with_game_count(): void
    {
        $rpg = Category::create(['name' => 'RPG']);
        Game::factory()->count(2)->for($rpg)->create();

        $this->get('/categories')->assertOk()->assertSee('RPG')->assertSee('Modifier');
    }

    public function test_category_is_created(): void
    {
        $this->post('/categories', ['name' => 'Puzzle'])
            ->assertRedirect('/categories')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', ['name' => 'Puzzle']);
    }

    public function test_name_is_required_short_and_unique(): void
    {
        Category::create(['name' => 'RPG']);

        $this->post('/categories', ['name' => ''])->assertSessionHasErrors('name');
        $this->post('/categories', ['name' => str_repeat('a', 51)])->assertSessionHasErrors('name');
        $this->post('/categories', ['name' => 'RPG'])->assertSessionHasErrors('name');
    }

    public function test_category_can_keep_its_own_name_on_update(): void
    {
        $rpg = Category::create(['name' => 'RPG']);
        Category::create(['name' => 'Action']);

        $this->put("/categories/{$rpg->id}", ['name' => 'RPG'])->assertSessionHasNoErrors();
        $this->put("/categories/{$rpg->id}", ['name' => 'Action'])->assertSessionHasErrors('name');
    }

    public function test_category_with_games_cannot_be_deleted(): void
    {
        $rpg = Category::create(['name' => 'RPG']);
        Game::factory()->for($rpg)->create();

        $this->delete("/categories/{$rpg->id}")
            ->assertRedirect('/categories')
            ->assertSessionHas('error', 'Cette catégorie contient encore des jeux.');

        $this->assertModelExists($rpg);
    }

    public function test_empty_category_is_deleted(): void
    {
        $empty = Category::create(['name' => 'Vide']);

        $this->delete("/categories/{$empty->id}")->assertSessionHas('success');
        $this->assertModelMissing($empty);
    }
}
