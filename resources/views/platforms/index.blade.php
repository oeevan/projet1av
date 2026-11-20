@extends('layouts.app')

@section('title', 'WishFlix - Plateformes')

@section('content')
  <section class="wish-section" aria-labelledby="admin-title">
    <header class="wish-section__header">
      <div>
        <h1 class="wish-section__title" id="admin-title">Plateformes</h1>
        <p class="wish-section__subtitle">{{ $platforms->count() }} au total</p>
      </div>
      <a class="btn btn-primary" href="{{ route('platforms.create') }}">Nouvelle plateforme</a>
    </header>

    <div class="overflow-x-auto">
      <table class="table">
        <thead>
          <tr><th>Nom</th><th>Jeux</th><th class="text-right">Actions</th></tr>
        </thead>
        <tbody>
          @forelse ($platforms as $platform)
            <tr>
              <td>{{ $platform->name }}</td>
              <td>{{ $platform->games_count }}</td>
              <td class="flex justify-end gap-2">
                <a class="btn btn-sm btn-outline" href="{{ route('platforms.edit', $platform) }}">Modifier</a>
                <form method="POST" action="{{ route('platforms.destroy', $platform) }}" onsubmit="return confirm('Supprimer « {{ $platform->name }} » ?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-error">Supprimer</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="3">Aucune plateforme pour le moment.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>
@endsection
