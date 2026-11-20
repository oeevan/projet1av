@extends('layouts.app')

@section('title', 'WishFlix - ' . $game->title)

@section('styles')
  @vite('resources/css/game-detail.css')
@endsection

@section('content')
        <section class="wish-detail">
          <figure class="wish-detail__backdrop">
            <img
              src="{{ $game->cover_url }}"
              alt="Image de couverture de {{ $game->title }}"
              width="1600"
              height="900"
            />
          </figure>

          <div class="wish-detail__veil" aria-hidden="true"></div>

          <div class="wish-detail__content">
            <a class="btn btn-ghost btn-sm" href="{{ route('home') }}">Retour au catalogue</a>

            <p class="wish-detail__kicker">Fiche jeu</p>
            <h1 class="wish-detail__title">{{ $game->title }}</h1>

            <div class="wish-detail__meta">
              <span class="badge badge-primary">{{ $game->category->name }}</span>
              <span class="badge badge-outline">{{ $game->release_year }}</span>
            </div>

            <p class="wish-detail__description">
              {{ $game->synopsis }}
            </p>

            <ul class="wish-detail__facts" aria-label="Informations sur le jeu">
              <li>
                <strong>Note:</strong>
                {{ $game->rating }}/5
              </li>
              <li>
                <strong>Duree:</strong>
                {{ $game->playtime_hours }}h de jeu
              </li>
              <li>
                <strong>Etat:</strong>
                {{ $game->status->label() }}
              </li>
              {{-- US2 : plateformes du jeu --}}
              <li>
                <strong>Plateformes:</strong>
                @forelse ($game->platforms as $platform)
                  <span class="badge badge-ghost">{{ $platform->name }}</span>
                @empty
                  Non renseigné
                @endforelse
              </li>
            </ul>

            <div class="wish-detail__actions">
              <button class="btn btn-secondary">Ajouter a la wishlist</button>
              <a class="btn btn-outline" href="{{ route('wishlist') }}"
                >Voir ma wishlist</a
              >
              <a class="btn btn-outline" href="{{ route('games.edit', $game->id) }}">Modifier</a>
              <form method="POST" action="{{ route('games.destroy', $game->id) }}" onsubmit="return confirm('Supprimer ce jeu ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-error">Supprimer</button>
              </form>
            </div>
          </div>
        </section>
@endsection
