{{-- Formulaire « un seul champ name », partagé par catégories et plateformes --}}
<section class="wish-login">
  <div class="card">
    <div class="card-body">
      <h1 class="card-title">{{ $heading }}</h1>

      <form method="POST" action="{{ $action }}">
        @csrf
        @isset($method) @method($method) @endisset

        <fieldset class="fieldset">
          <legend class="fieldset-legend">Nom</legend>
          <input type="text" name="name" value="{{ old('name', $value ?? '') }}" class="input w-full" autofocus />
          @error('name') <p class="text-error text-sm">{{ $message }}</p> @enderror
        </fieldset>

        <div class="flex gap-2 mt-4">
          <button type="submit" class="btn btn-primary">Enregistrer</button>
          <a class="btn btn-ghost" href="{{ $back }}">Annuler</a>
        </div>
      </form>
    </div>
  </div>
</section>
