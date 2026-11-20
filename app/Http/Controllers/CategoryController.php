<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return view('categories.index', [
            'categories' => Category::withCount('games')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());

        return redirect()->route('categories.index')->with('success', 'Catégorie créée !');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', ['category' => $category]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()->route('categories.index')->with('success', 'Catégorie modifiée !');
    }

    public function destroy(Category $category)
    {
        // Un jeu doit toujours avoir une catégorie : on refuse plutôt que de casser la clé étrangère
        if ($category->games()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', 'Cette catégorie contient encore des jeux.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée !');
    }
}
