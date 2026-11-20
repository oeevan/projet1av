@extends('layouts.app')

@section('title', 'WishFlix - Modifier la catégorie')

@section('styles')
  @vite('resources/css/login.css')
@endsection

@section('content')
  @include('partials.name-form', [
      'heading' => 'Modifier la catégorie',
      'action' => route('categories.update', $category),
      'method' => 'PUT',
      'value' => $category->name,
      'back' => route('categories.index'),
  ])
@endsection
