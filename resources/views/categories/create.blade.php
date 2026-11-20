@extends('layouts.app')

@section('title', 'WishFlix - Nouvelle catégorie')

@section('styles')
  @vite('resources/css/login.css')
@endsection

@section('content')
  @include('partials.name-form', [
      'heading' => 'Nouvelle catégorie',
      'action' => route('categories.store'),
      'back' => route('categories.index'),
  ])
@endsection
