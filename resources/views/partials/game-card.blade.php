<article class="card movie-card game-card" aria-label="Jeu {{ $game->title }}">
  <figure class="game-card__cover">
    <img src="{{ $game->cover_src }}" alt="Affiche de {{ $game->title }}" width="300" height="450" loading="lazy" />
  </figure>

  <div class="card-body game-card__body">
    <div class="game-card__badges">
      <span class="badge badge-secondary">{{ $game->category->name }}</span>
      <span class="badge badge-success">{{ $game->status->label() }}</span>
    </div>

    <h3 class="card-title game-card__title">{{ $game->title }}</h3>
    <p class="game-card__meta">{{ $game->release_year }}</p>

    <div class="game-card__rating" aria-label="Note moyenne">
      <span class="game-card__rating-value">{{ $game->rating }}</span>
      <span class="game-card__rating-max">/5</span>
    </div>

    <p class="game-card__synopsis">{{ $game->synopsis }}</p>

    <div class="card-actions game-card__actions">
      <a class="btn btn-sm btn-ghost" href="{{ route('games.show', $game) }}" aria-label="Voir les details de {{ $game->title }}">
        Details
      </a>
      <button class="btn btn-sm btn-outline">Wishlist</button>
    </div>
  </div>
</article>
