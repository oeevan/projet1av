<?php

namespace App\Models;

use App\Enums\GameStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'synopsis',
        'release_year',
        'rating',
        'playtime_hours',
        'cover_url',
        'featured',
        'status',
        'category_id',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'status' => GameStatus::class,
        ];
    }

    /**
     * Image à afficher : la jaquette uploadée en priorité, sinon l'URL.
     */
    protected function coverSrc(): Attribute
    {
        return Attribute::get(fn () => $this->cover_path
            ? asset('storage/'.$this->cover_path)
            : $this->cover_url);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class);
    }
}
