<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    private array $games = [
        1 => [
            'title' => 'The Witcher 3',
            'synopsis' => 'Incarnez Geralt de Riv, un chasseur de monstres professionnel, dans un monde ouvert fantastique.',
            'release_year' => 2015,
            'rating' => 4.8,
            'category' => 'RPG',
            'platforms' => 'PC, PS5, Xbox',
            'cover_url' => 'https://via.assets.so/game.png?id=1&q=95&w=300&h=450&fit=cover',
        ],
        2 => [
            'title' => 'Elden Ring',
            'synopsis' => "Un RPG d'action en monde ouvert développé par FromSoftware et George R.R. Martin.",
            'release_year' => 2022,
            'rating' => 4.9,
            'category' => 'Action',
            'platforms' => 'PC, PS5, Xbox',
            'cover_url' => 'https://via.assets.so/game.png?id=2&q=95&w=300&h=450&fit=cover',
        ],
        3 => [
            'title' => 'Cyberpunk 2077',
            'synopsis' => 'Plongez dans Night City, une mégalopole obsédée par le pouvoir, le glamour et les modifications corporelles.',
            'release_year' => 2020,
            'rating' => 4.5,
            'category' => 'RPG',
            'platforms' => 'PC, PS5, Xbox',
            'cover_url' => 'https://via.assets.so/game.png?id=3&q=95&w=300&h=450&fit=cover',
        ],
        4 => [
            'title' => 'Red Dead Redemption 2',
            'synopsis' => "L'épopée de hors-la-loi Arthur Morgan et de la bande de Dutch van der Linde.",
            'release_year' => 2018,
            'rating' => 4.7,
            'category' => 'Aventure',
            'platforms' => 'PC, PS5, Xbox',
            'cover_url' => 'https://via.assets.so/game.png?id=4&q=95&w=300&h=450&fit=cover',
        ],
    ];

    public function index(){
            return view('home', ['games' => $this->games]);
        }
}