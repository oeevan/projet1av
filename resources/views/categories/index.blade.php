@extends('layouts.app')

@section('title', 'WishFlix - Catégories')

@section('content')
  <section class="wish-section" aria-labelledby="admin-title">
    <header class="wish-section__header">
      <div>
        <h1 class="wish-section__title" id="admin-title">Catégories</h1>
        <p class="wish-section__subtitle">{{ $categories->count() }} au total</p>
      </div>
      <a class="btn btn-primary" href="{{ route('categories.create') }}">Nouvelle catégorie</a>
    </header>

    <div class="overflow-x-auto">
      <table class="table">
        <thead>
          <tr><th>Nom</th><th>Jeux</th><th class="text-right">Actions</th></tr>
        </thead>
        <tbody>
          @forelse ($categories as $category)
            <tr>
              <td>{{ $category->name }}</td>
              <td>{{ $category->games_count }}</td>
              <td class="flex justify-end gap-2">
                <a class="btn btn-sm btn-outline" href="{{ route('categories.edit', $category) }}">Modifier</a>
                <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Supprimer « {{ $category->name }} » ?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-error">Supprimer</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="3">Aucune catégorie pour le moment.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>
@endsection
