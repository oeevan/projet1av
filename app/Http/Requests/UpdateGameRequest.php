<?php

namespace App\Http\Requests;

/**
 * Mêmes règles qu'à la création : on hérite de StoreGameRequest
 * plutôt que de recopier le tableau (un seul endroit à maintenir).
 */
class UpdateGameRequest extends StoreGameRequest {}
