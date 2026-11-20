<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlatformRequest;
use App\Http\Requests\UpdatePlatformRequest;
use App\Models\Platform;

class PlatformController extends Controller
{
    public function index()
    {
        return view('platforms.index', [
            'platforms' => Platform::withCount('games')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('platforms.create');
    }

    public function store(StorePlatformRequest $request)
    {
        Platform::create($request->validated());

        return redirect()->route('platforms.index')->with('success', 'Plateforme créée !');
    }

    public function edit(Platform $platform)
    {
        return view('platforms.edit', ['platform' => $platform]);
    }

    public function update(UpdatePlatformRequest $request, Platform $platform)
    {
        $platform->update($request->validated());

        return redirect()->route('platforms.index')->with('success', 'Plateforme modifiée !');
    }

    public function destroy(Platform $platform)
    {
        $platform->games()->detach(); // libère la pivot game_platform avant la suppression
        $platform->delete();

        return redirect()->route('platforms.index')->with('success', 'Plateforme supprimée !');
    }
}
