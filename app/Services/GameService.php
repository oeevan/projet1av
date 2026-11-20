<?php

namespace App\Services;

use App\Models\Game;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Règles métier des jeux, hors du contrôleur (bonus 2, S5) :
 * slug unique, plateformes, jaquette uploadée et « à la une » unique.
 */
class GameService
{
    public function create(array $data): Game
    {
        return DB::transaction(function () use ($data) {
            $game = new Game(['slug' => $this->uniqueSlug($data['title'])]);

            return $this->save($game, $data);
        });
    }

    public function update(Game $game, array $data): Game
    {
        // Le slug ne change pas : l'URL de la fiche reste stable
        return DB::transaction(fn () => $this->save($game, $data));
    }

    public function delete(Game $game): void
    {
        DB::transaction(function () use ($game) {
            $game->platforms()->detach(); // sinon la clé étrangère de game_platform bloque (erreur 500)
            $game->delete();
        });

        $this->deleteCover($game->cover_path);
    }

    private function save(Game $game, array $data): Game
    {
        $game->fill(Arr::except($data, ['platform_ids', 'cover']));

        if (($data['cover'] ?? null) instanceof UploadedFile) {
            $old = $game->cover_path;
            $game->cover_path = $data['cover']->store('covers', 'public');
            $this->deleteCover($old);
        }

        $game->save();
        $game->platforms()->sync($data['platform_ids'] ?? []);

        // Bonus 1 : un seul jeu « à la une » à la fois
        if ($game->featured) {
            Game::whereKeyNot($game->id)->where('featured', true)->update(['featured' => false]);
        }

        return $game;
    }

    private function deleteCover(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
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
