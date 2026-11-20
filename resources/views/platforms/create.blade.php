@extends('layouts.app')

@section('title', 'WishFlix - Nouvelle plateforme')

@section('styles')
  @vite('resources/css/login.css')
@endsection

@section('content')
  @include('partials.name-form', [
      'heading' => 'Nouvelle plateforme',
      'action' => route('platforms.store'),
      'back' => route('platforms.index'),
  ])
@endsection
