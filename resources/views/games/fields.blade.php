{{-- Champs communs à games/create et games/edit ($game vaut null à la création) --}}
<fieldset class="fieldset">
  <legend class="fieldset-legend">Titre</legend>
  <input type="text" name="title" value="{{ old('title', $game?->title) }}" class="input w-full" />
  @error('title') <p class="text-error text-sm">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="fieldset">
  <legend class="fieldset-legend">Synopsis</legend>
  <textarea name="synopsis" class="textarea w-full">{{ old('synopsis', $game?->synopsis) }}</textarea>
  @error('synopsis') <p class="text-error text-sm">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="fieldset">
  <legend class="fieldset-legend">Année de sortie</legend>
  <input type="number" name="release_year" value="{{ old('release_year', $game?->release_year) }}" class="input w-full" />
  @error('release_year') <p class="text-error text-sm">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="fieldset">
  <legend class="fieldset-legend">Note</legend>
  <input type="number" step="0.1" name="rating" value="{{ old('rating', $game?->rating) }}" class="input w-full" />
  @error('rating') <p class="text-error text-sm">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="fieldset">
  <legend class="fieldset-legend">Durée de jeu (heures)</legend>
  <input type="number" name="playtime_hours" value="{{ old('playtime_hours', $game?->playtime_hours) }}" class="input w-full" />
  @error('playtime_hours') <p class="text-error text-sm">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="fieldset">
  <legend class="fieldset-legend">URL de la jaquette</legend>
  <input type="text" name="cover_url" value="{{ old('cover_url', $game?->cover_url) }}" class="input w-full" />
  @error('cover_url') <p class="text-error text-sm">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="fieldset">
  <legend class="fieldset-legend">… ou fichier image (2 Mo max)</legend>
  <input type="file" name="cover" accept="image/*" class="file-input w-full" />
  @if ($game?->cover_path)
    <p class="text-sm opacity-70">Jaquette actuelle : <a class="link" href="{{ $game->cover_src }}" target="_blank">voir l'image</a></p>
  @endif
  @error('cover') <p class="text-error text-sm">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="fieldset">
  <legend class="fieldset-legend">Statut</legend>
  <select name="status" class="select w-full">
    <option value="released" @selected(old('status', $game?->status->value) == 'released')>Sorti</option>
    <option value="upcoming" @selected(old('status', $game?->status->value) == 'upcoming')>À venir</option>
    <option value="early_access" @selected(old('status', $game?->status->value) == 'early_access')>Accès anticipé</option>
  </select>
  @error('status') <p class="text-error text-sm">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="fieldset">
  <legend class="fieldset-legend">Catégorie</legend>
  <select name="category_id" class="select w-full">
    @foreach ($categories as $category)
      <option value="{{ $category->id }}" @selected(old('category_id', $game?->category_id) == $category->id)>{{ $category->name }}</option>
    @endforeach
  </select>
  @error('category_id') <p class="text-error text-sm">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="fieldset">
  <legend class="fieldset-legend">Plateformes</legend>
  @foreach ($platforms as $platform)
    <label class="label">
      <input type="checkbox" name="platform_ids[]" value="{{ $platform->id }}" class="checkbox"
             @checked(in_array($platform->id, old('platform_ids', $game?->platforms->pluck('id')->all() ?? []))) />
      {{ $platform->name }}
    </label>
  @endforeach
  @error('platform_ids') <p class="text-error text-sm">{{ $message }}</p> @enderror
  @error('platform_ids.*') <p class="text-error text-sm">{{ $message }}</p> @enderror
</fieldset>

<fieldset class="fieldset">
  {{-- Une case décochée n'est pas envoyée : le champ caché garantit featured=0 --}}
  <input type="hidden" name="featured" value="0" />
  <label class="label">
    <input type="checkbox" name="featured" value="1" class="checkbox" @checked(old('featured', $game?->featured)) />
    À la une
  </label>
  @error('featured') <p class="text-error text-sm">{{ $message }}</p> @enderror
</fieldset>
