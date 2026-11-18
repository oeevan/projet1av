<!doctype html>
<html lang="fr" data-theme="dark">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>
    @vite(['resources/css/app.css'])
    @yield('styles')
  </head>
  <body>
    <div class="wishflix-shell">
      @include('partials.nav')

      <main class="wishflix-main" id="contenu-principal">
        @yield('content')
      </main>

      <footer class="wishflix-footer" aria-label="Pied de page">
        <p class="wishflix-footer__text">WishFlix - Gaming Catalog</p>
      </footer>
    </div>
  </body>
</html>
