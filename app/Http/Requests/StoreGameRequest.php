<?php

namespace App\Http\Requests;

use App\Enums\GameStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Ouvert à tous pour l'instant : réservé aux administrateurs en S6
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'synopsis' => ['required', 'string'],
            'release_year' => ['required', 'integer', 'between:1970,2100'],
            'rating' => ['required', 'numeric', 'between:0,10'],
            'playtime_hours' => ['required', 'integer', 'min:0'],
            // Une jaquette : soit une URL, soit un fichier image (bonus 3)
            'cover_url' => ['nullable', 'required_without:cover', 'url'],
            'cover' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', Rule::enum(GameStatus::class)],
            'category_id' => ['required', 'exists:categories,id'],
            'platform_ids' => ['nullable', 'array'],
            'platform_ids.*' => ['integer', 'exists:platforms,id'],
            'featured' => ['boolean'],
        ];
    }
}
