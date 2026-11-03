@extends('layouts.app')

@section('title', 'WishFlix - Connexion')

@section('styles')
  @vite('resources/css/login.css')
@endsection

{{-- GET uniquement : le formulaire sera traité en S6 --}}
@section('content')
        <section class="wish-login" aria-labelledby="login-title">
          <div class="card wish-login__card">
            <div class="card-body">
              <h1 id="login-title" class="card-title">Connexion</h1>
              <p class="wish-login__intro">
                Connectez-vous pour proteger votre wishlist.
              </p>

              <form class="wish-login__form" novalidate>
                <label class="form-control" for="email">
                  <span class="label-text">Email</span>
                  <input id="email" type="email" class="input input-bordered" />
                </label>

                <label class="form-control" for="password">
                  <span class="label-text">Mot de passe</span>
                  <input
                    id="password"
                    type="password"
                    class="input input-bordered"
                  />
                </label>

                <button class="btn btn-primary" type="submit">
                  Se connecter
                </button>
              </form>

              <div class="divider"></div>

              <p class="wish-login__demo-title">Compte demo</p>
              <p><strong>Email:</strong> demo@wishflix.com</p>
              <p><strong>Mot de passe:</strong> demo1234</p>

              <a class="link link-primary" href="{{ route('home') }}">Retour au catalogue</a>
            </div>
          </div>
        </section>
@endsection
