@extends('layouts.app')

@section('title', 'WishFlix - Nouveau jeu')

@section('styles')
  @vite('resources/css/login.css')
@endsection

@section('content')
<section class="wish-login">
  <div class="card">
    <div class="card-body">
      <h1 class="card-title">Nouveau jeu</h1>

      <form method="POST" action="{{ route('games.store') }}">
        @csrf

        @include('games.fields', ['game' => null])

        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </form>
    </div>
  </div>
</section>
@endsection
