<?php

namespace App\Http\Requests;

/**
 * Mêmes règles qu'à la création : on hérite de StoreGameRequest
 * plutôt que de recopier le tableau (un seul endroit à maintenir).
 */
class UpdateGameRequest extends StoreGameRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        // Un jeu qui a déjà une jaquette uploadée peut garder une URL vide
        if ($this->route('game')->cover_path) {
            $rules['cover_url'] = ['nullable', 'url'];
        }

        return $rules;
    }
}
