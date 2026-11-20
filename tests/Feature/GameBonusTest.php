<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GameBonusTest extends TestCase
{
    use RefreshDatabase;

    private function data(array $overrides = []): array
    {
        return [
            'title' => 'Hades',
            'synopsis' => 'Fuir les Enfers.',
            'release_year' => 2020,
            'rating' => 9.5,
            'playtime_hours' => 30,
            'cover_url' => 'https://via.assets.so/game.png?id=60',
            'status' => 'released',
            'category_id' => Category::factory()->create()->id,
            'featured' => '0',
            ...$overrides,
        ];
    }

    public function test_only_one_featured_game_on_create_and_update(): void
    {
        $old = Game::factory()->featured()->create();

        $this->post('/games', $this->data(['featured' => '1']));
        $new = Game::where('title', 'Hades')->firstOrFail();

        $this->assertTrue($new->featured);
        $this->assertFalse($old->fresh()->featured);

        $this->put("/games/{$old->id}", $this->data(['title' => $old->title, 'featured' => '1']));
        $this->assertTrue($old->fresh()->featured);
        $this->assertFalse($new->fresh()->featured);
    }

    public function test_cover_upload_is_stored_and_displayed(): void
    {
        Storage::fake('public');

        $this->post('/games', $this->data([
            'cover_url' => '',
            'cover' => UploadedFile::fake()->image('hades.jpg', 300, 450),
        ]))->assertSessionHasNoErrors();

        $game = Game::where('title', 'Hades')->firstOrFail();
        Storage::disk('public')->assertExists($game->cover_path);
        $this->get(route('games.show', $game))->assertSee('storage/'.$game->cover_path);
    }

    public function test_cover_must_be_an_image_and_an_url_or_file_is_required(): void
    {
        $this->post('/games', $this->data(['cover_url' => '']))->assertSessionHasErrors('cover_url');
        $this->post('/games', $this->data(['cover' => UploadedFile::fake()->create('doc.pdf', 10)]))->assertSessionHasErrors('cover');
        $this->post('/games', $this->data(['cover' => UploadedFile::fake()->image('big.jpg')->size(3000)]))->assertSessionHasErrors('cover');
    }

    public function test_replacing_and_deleting_cover_removes_old_files(): void
    {
        Storage::fake('public');
        $this->post('/games', $this->data(['cover' => UploadedFile::fake()->image('a.jpg')]));
        $game = Game::where('title', 'Hades')->firstOrFail();
        $first = $game->cover_path;

        $this->put("/games/{$game->id}", $this->data(['cover_url' => '', 'cover' => UploadedFile::fake()->image('b.jpg')]))
            ->assertSessionHasNoErrors();
        Storage::disk('public')->assertMissing($first);

        $second = $game->fresh()->cover_path;
        $this->delete("/games/{$game->id}");
        Storage::disk('public')->assertMissing($second);
    }
}
