@extends('layouts.app')

@section('title', 'WishFlix - Accueil')

@section('styles')
  @vite('resources/css/home.css')
@endsection

@section('content')
  {{-- S4 A1 : le jeu « à la une » (null si aucun jeu featured) --}}
  @if ($featured)
    <section class="hero wishflix-hero" aria-labelledby="hero-title">
      <figure class="wishflix-hero__backdrop">
        <img src="{{ $featured->cover_src }}" alt="Image de couverture de {{ $featured->title }}" width="1600" height="900" />
      </figure>
      <div class="wishflix-hero__veil" aria-hidden="true"></div>

      <div class="hero-content wishflix-hero__content">
        <div class="wishflix-hero__text">
          <p class="wishflix-hero__kicker">Nouveaute WishFlix</p>
          <div class="wishflix-hero__meta">
            <span class="badge badge-primary">A la une</span>
            <span class="badge badge-ghost">{{ $featured->category->name }}</span>
            <span class="badge badge-outline">{{ $featured->platforms->pluck('name')->join(', ') }}</span>
          </div>

          <h1 id="hero-title" class="wishflix-hero__title">{{ $featured->title }}</h1>
          <p class="wishflix-hero__description">{{ $featured->synopsis }}</p>
          <p class="wishflix-hero__details">{{ $featured->release_year }} | {{ $featured->rating }}/5 | {{ $featured->playtime_hours }}h de jeu</p>

          <div class="wishflix-hero__actions">
            <a class="btn btn-primary btn-lg" href="{{ route('games.show', $featured) }}">Voir la fiche</a>
            <button class="btn btn-outline btn-lg">Ajouter a la wishlist</button>
          </div>
        </div>
      </div>
    </section>
  @endif

  {{-- S4 A1 : les 6 mieux notés --}}
  <section class="wish-section" aria-labelledby="top-title">
    <header class="wish-section__header">
      <div>
        <h2 class="wish-section__title" id="top-title">Mieux notés</h2>
        <p class="wish-section__subtitle">Les 6 meilleures notes du catalogue</p>
      </div>
    </header>

    <div class="wish-section__content">
      <div class="wish-home__grid">
        @foreach ($topRated as $game)
          @include('partials.game-card')
        @endforeach
      </div>
    </div>
  </section>

  <section class="wish-section" aria-labelledby="catalogue-title">
    <header class="wish-section__header">
      <div>
        <h2 class="wish-section__title" id="catalogue-title">Catalogue</h2>
        <p class="wish-section__subtitle">Recherche, filtre par catégorie et pagination</p>
      </div>

      {{-- S4 A2 + US1/US2 : formulaire GET, les valeurs restent dans l'URL --}}
      <div class="wish-section__actions">
        <form class="wish-home__filters" method="GET" action="{{ route('home') }}">
          <label class="form-control wish-home__search" for="game-search">
            <span class="label-text">Rechercher</span>
            <input id="game-search" type="search" name="search" value="{{ request('search') }}"
                   class="input input-bordered" placeholder="Titre ou mot-cle" />
          </label>

          <label class="form-control" for="game-category">
            <span class="label-text">Categorie</span>
            <select id="game-category" name="category" class="select select-bordered">
              <option value="">Toutes les catégories</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>
                  {{ $category->name }} ({{ $category->games_count }})
                </option>
              @endforeach
            </select>
          </label>

          <button type="submit" class="btn btn-secondary">Filtrer</button>
          <a class="btn btn-ghost" href="{{ route('home') }}">Réinitialiser</a>
        </form>
      </div>
    </header>

    <div class="wish-section__stats">
      <div class="stats shadow wish-home__stats" role="group" aria-label="Statistiques catalogue">
        <div class="stat">
          <div class="stat-title">Jeux disponibles</div>
          <div class="stat-value text-primary">{{ $categories->sum('games_count') }}</div>
        </div>

        <div class="stat">
          <div class="stat-title">Resultats filtres</div>
          <div class="stat-value text-secondary">{{ $games->total() }}</div>
        </div>

        <div class="stat">
          <div class="stat-title">Ma wishlist</div>
          <div class="stat-value text-accent">3</div>
        </div>
      </div>
    </div>

    <div class="wish-section__content">
      <div class="wish-home__grid">
        @forelse ($games as $game)
          @include('partials.game-card')
        @empty
          <p class="wish-home__empty">Aucun jeu ne correspond à votre recherche.</p>
        @endforelse
      </div>

      {{-- S4 A6 : liens de pagination (gardent search/category grâce à withQueryString) --}}
      <div class="mt-8">
        {{ $games->links() }}
      </div>
    </div>
  </section>

  {{-- S4 bonus US3 : catégories classées par note moyenne (une seule requête) --}}
  <section class="wish-section" aria-labelledby="best-categories-title">
    <header class="wish-section__header">
      <div>
        <h2 class="wish-section__title" id="best-categories-title">Catégories les mieux notées</h2>
        <p class="wish-section__subtitle">Note moyenne des jeux de chaque catégorie</p>
      </div>
    </header>

    <ol class="wish-section__stats">
      @foreach ($bestCategories as $category)
        <li class="badge badge-outline badge-lg">
          {{ $loop->iteration }}. {{ $category->name }} — {{ number_format($category->games_avg_rating ?? 0, 1) }}/5
        </li>
      @endforeach
    </ol>
  </section>
@endsection
