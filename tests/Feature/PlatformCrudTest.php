<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Platform;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_platform_crud(): void
    {
        $this->get('/platforms')->assertOk();

        $this->post('/platforms', ['name' => 'Steam Deck'])->assertRedirect('/platforms')->assertSessionHas('success');
        $deck = Platform::where('name', 'Steam Deck')->firstOrFail();

        $this->get("/platforms/{$deck->id}/edit")->assertOk()->assertSee('value="Steam Deck"', false);
        $this->put("/platforms/{$deck->id}", ['name' => 'Switch 2'])->assertSessionHas('success');
        $this->assertSame('Switch 2', $deck->fresh()->name);
    }

    public function test_name_is_required_and_unique(): void
    {
        Platform::create(['name' => 'PC']);

        $this->post('/platforms', ['name' => ''])->assertSessionHasErrors('name');
        $this->post('/platforms', ['name' => 'PC'])->assertSessionHasErrors('name');
    }

    public function test_platform_is_detached_from_games_before_deletion(): void
    {
        $pc = Platform::create(['name' => 'PC']);
        Game::factory()->create()->platforms()->attach($pc);

        $this->delete("/platforms/{$pc->id}")->assertRedirect('/platforms')->assertSessionHas('success');

        $this->assertModelMissing($pc);
        $this->assertDatabaseCount('game_platform', 0);
    }
}
