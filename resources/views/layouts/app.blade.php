<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>
    <link
      href="https://cdn.jsdelivr.net/npm/daisyui@5"
      rel="stylesheet"
      type="text/css"
    />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
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
