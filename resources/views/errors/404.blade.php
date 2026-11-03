@extends('layouts.app')

@section('title', 'WishFlix - Page introuvable')

@section('styles')
  @vite('resources/css/404.css')
@endsection

@section('content')
        <section class="wish-not-found" aria-labelledby="not-found-title">
          <h1 id="not-found-title">404</h1>
          <p>Oups, cette page n existe pas.</p>
          <a class="btn btn-primary" href="{{ route('home') }}">Retourner au catalogue</a>
        </section>
@endsection
