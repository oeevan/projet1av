@extends('layouts.app')

@section('title', 'WishFlix - Ma wishlist')

@section('styles')
  @vite('resources/css/wishlist.css')
@endsection

{{-- Contenu statique : les vraies données utilisateur arrivent en S6 --}}
@section('content')
        <section class="wish-wishlist">
          <header class="wish-wishlist__header">
            <h1>Ma wishlist</h1>
            <p>Les jeux que vous avez mis de cote pour plus tard.</p>
          </header>

          <div class="wish-wishlist__grid">
            <article
              class="card movie-card game-card"
              aria-label="Jeu Elden Ring"
            >
              <figure class="game-card__cover">
                <img
                  src="https://via.assets.so/game.png?id=2&q=95&w=300&h=450&fit=cover"
                  alt="Affiche de Elden Ring"
                  width="300"
                  height="450"
                />
              </figure>

              <div class="card-body game-card__body">
                <div class="game-card__badges">
                  <span class="badge badge-secondary">Action</span>
                  <span class="badge badge-success">Disponible</span>
                </div>

                <h3 class="card-title game-card__title">Elden Ring</h3>
                <p class="game-card__meta">2022 - PC, PS5, Xbox</p>

                <div class="game-card__rating" aria-label="Note moyenne">
                  <span class="game-card__rating-value">4.9</span>
                  <span class="game-card__rating-max">/5</span>
                </div>

                <p class="game-card__synopsis">
                  Un RPG d'action en monde ouvert développé par FromSoftware et
                  George R.R. Martin.
                </p>

                <div class="card-actions game-card__actions">
                  <a class="btn btn-sm btn-ghost" href="{{ route('games.show', 'elden-ring') }}">
                    Details
                  </a>
                  <button class="btn btn-sm btn-secondary">Retirer</button>
                </div>
              </div>
            </article>

            <article
              class="card movie-card game-card"
              aria-label="Jeu Red Dead Redemption 2"
            >
              <figure class="game-card__cover">
                <img
                  src="https://via.assets.so/game.png?id=4&q=95&w=300&h=450&fit=cover"
                  alt="Affiche de Red Dead Redemption 2"
                  width="300"
                  height="450"
                />
              </figure>

              <div class="card-body game-card__body">
                <div class="game-card__badges">
                  <span class="badge badge-secondary">Aventure</span>
                  <span class="badge badge-success">Disponible</span>
                </div>

                <h3 class="card-title game-card__title">
                  Red Dead Redemption 2
                </h3>
                <p class="game-card__meta">2018 - PC, PS5, Xbox</p>

                <div class="game-card__rating" aria-label="Note moyenne">
                  <span class="game-card__rating-value">4.7</span>
                  <span class="game-card__rating-max">/5</span>
                </div>

                <p class="game-card__synopsis">
                  L'épopée de hors-la-loi Arthur Morgan et de la bande de Dutch
                  van der Linde.
                </p>

                <div class="card-actions game-card__actions">
                  <a class="btn btn-sm btn-ghost" href="{{ route('games.show', 'red-dead-redemption-2') }}">
                    Details
                  </a>
                  <button class="btn btn-sm btn-secondary">Retirer</button>
                </div>
              </div>
            </article>

            <article
              class="card movie-card game-card"
              aria-label="Jeu God of War"
            >
              <figure class="game-card__cover">
                <img
                  src="https://via.assets.so/game.png?id=5&q=95&w=300&h=450&fit=cover"
                  alt="Affiche de God of War"
                  width="300"
                  height="450"
                />
              </figure>

              <div class="card-body game-card__body">
                <div class="game-card__badges">
                  <span class="badge badge-secondary">Action</span>
                  <span class="badge badge-success">Disponible</span>
                </div>

                <h3 class="card-title game-card__title">God of War</h3>
                <p class="game-card__meta">2018 - PC, PS5</p>

                <div class="game-card__rating" aria-label="Note moyenne">
                  <span class="game-card__rating-value">4.8</span>
                  <span class="game-card__rating-max">/5</span>
                </div>

                <p class="game-card__synopsis">
                  Kratos et son fils Atreus entreprennent un voyage épique dans
                  les royaumes nordiques.
                </p>

                <div class="card-actions game-card__actions">
                  <a class="btn btn-sm btn-ghost" href="{{ route('games.show', 'god-of-war') }}">
                    Details
                  </a>
                  <button class="btn btn-sm btn-secondary">Retirer</button>
                </div>
              </div>
            </article>
          </div>
        </section>
@endsection
