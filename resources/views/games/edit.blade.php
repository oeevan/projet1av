@extends('layouts.app')

@section('title', 'WishFlix - Modifier le jeu')

@section('styles')
  @vite('resources/css/login.css')
@endsection

@section('content')
<section class="wish-login">
  <div class="card">
    <div class="card-body">
      <h1 class="card-title">Modifier le jeu</h1>

      <form method="POST" action="{{ route('games.update', $game->id) }}">
        @csrf
        @method('PUT')

        @include('games.fields')

        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </form>
    </div>
  </div>
</section>
@endsection
