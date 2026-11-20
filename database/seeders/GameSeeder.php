<?php

namespace Database\Seeders;

use App\Enums\GameStatus;
use App\Models\Category;
use App\Models\Game;
use App\Models\Platform;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');
        $platforms = Platform::pluck('id', 'name');

        // Les 4 jeux du tableau PHP de la S2, + 2 nouveaux
        $games = [
            [
                'title' => 'The Witcher 3',
                'synopsis' => 'Incarnez Geralt de Riv, un chasseur de monstres professionnel, dans un monde ouvert fantastique.',
                'release_year' => 2015,
                'rating' => 4.8,
                'playtime_hours' => 100,
                'category' => 'RPG',
                'platforms' => ['PC', 'PS5', 'Xbox'],
            ],
            [
                'title' => 'Elden Ring',
                'synopsis' => "Un RPG d'action en monde ouvert développé par FromSoftware et George R.R. Martin.",
                'release_year' => 2022,
                'rating' => 4.9,
                'playtime_hours' => 80,
                'category' => 'Action',
                'platforms' => ['PC', 'PS5', 'Xbox'],
            ],
            [
                'title' => 'Cyberpunk 2077',
                'synopsis' => 'Plongez dans Night City, une mégalopole obsédée par le pouvoir, le glamour et les modifications corporelles.',
                'release_year' => 2020,
                'rating' => 4.5,
                'playtime_hours' => 60,
                'category' => 'RPG',
                'platforms' => ['PC', 'PS5'],
            ],
            [
                'title' => 'Red Dead Redemption 2',
                'synopsis' => "L'épopée de hors-la-loi Arthur Morgan et de la bande de Dutch van der Linde.",
                'release_year' => 2018,
                'rating' => 4.7,
                'playtime_hours' => 70,
                'category' => 'Aventure',
                'platforms' => ['PC', 'PS5', 'Xbox'],
            ],
            [
                'title' => 'God of War',
                'synopsis' => 'Kratos et son fils Atreus entreprennent un voyage épique dans les royaumes nordiques.',
                'release_year' => 2018,
                'rating' => 4.8,
                'playtime_hours' => 30,
                'category' => 'Action',
                'platforms' => ['PC', 'PS5'],
            ],
            [
                'title' => 'Civilization VI',
                'synopsis' => 'Bâtissez un empire qui résistera à l\'épreuve du temps, de l\'Antiquité à l\'ère de l\'information.',
                'release_year' => 2016,
                'rating' => 4.4,
                'playtime_hours' => 150,
                'category' => 'Stratégie',
                'platforms' => ['PC', 'Switch'],
            ],
        ];

        foreach ($games as $index => $data) {
            $game = Game::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'synopsis' => $data['synopsis'],
                'release_year' => $data['release_year'],
                'rating' => $data['rating'],
                'playtime_hours' => $data['playtime_hours'],
                'cover_url' => 'https://via.assets.so/game.png?id='.($index + 1).'&q=95&w=300&h=450&fit=cover',
                'featured' => $data['featured'] ?? false,
                'status' => GameStatus::Released,
                'category_id' => $categories[$data['category']],
            ]);

            $game->platforms()->attach(
                collect($data['platforms'])->map(fn ($name) => $platforms[$name])
            );
        }

        // Partie B (US1) : ~14 jeux de plus via la factory, dont LE jeu « à la une » (un seul, bonus 1 S5)
        $allPlatforms = Platform::all();

        Game::factory()->count(13)
            ->recycle(Category::all())
            ->create()
            ->push(Game::factory()->featured()->recycle(Category::all())->create())
            ->each(fn ($game) => $game->platforms()->attach(
                $allPlatforms->random(rand(1, 3))->pluck('id')
            ));
    }
}
