  <header
    class="navbar wishflix-shell__navbar"
    aria-label="Navigation principale"
  >
    <div class="navbar-start">
      <a class="wishflix-shell__brand" href="{{ route('home') }}">WishFlix</a>
      <span class="wishflix-shell__tagline">Gaming catalog</span>
    </div>

    <nav
      class="navbar-center wishflix-shell__menu"
      aria-label="Liens principaux"
    >
      <a class="wishflix-shell__link wishflix-shell__link--active" href="{{ route('home') }}">
        Accueil
      </a>
      <a class="wishflix-shell__link" href="{{ route('wishlist') }}">
        Wishlist
        <span class="badge badge-secondary badge-sm">3</span>
      </a>
    </nav>

    <div class="navbar-end wishflix-shell__user">
      <span class="wishflix-shell__user-label">Invité</span>
      <a class="btn btn-primary btn-sm" href="{{ route('login') }}">Se connecter</a>
    </div>
  </header>
