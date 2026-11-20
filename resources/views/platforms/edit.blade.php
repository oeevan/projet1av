@extends('layouts.app')

@section('title', 'WishFlix - Modifier la plateforme')

@section('styles')
  @vite('resources/css/login.css')
@endsection

@section('content')
  @include('partials.name-form', [
      'heading' => 'Modifier la plateforme',
      'action' => route('platforms.update', $platform),
      'method' => 'PUT',
      'value' => $platform->name,
      'back' => route('platforms.index'),
  ])
@endsection
