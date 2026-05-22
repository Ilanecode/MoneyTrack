<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $toutes = Categorie::withCount('transactions')->get();
        $entrees = $toutes->where('type', 'entrée')->values();
        $sorties = $toutes->where('type', 'sortie')->values();
        
        return view('categories.index', compact('toutes', 'entrees', 'sorties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'  => 'required|string|max:100',
            'type' => 'required|in:entrée,sortie',
        ]);

        Categorie::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Catégorie ajoutée avec succès !');
    }

    public function destroy(Categorie $categorie)
    {
        $categorie->delete();
        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée.');
    }

    public function update(Request $request, Categorie $categorie)
    {
        $request->validate([
            'nom'  => 'required|string|max:100',
            'type' => 'required|in:entrée,sortie',
        ]);

        $categorie->update($request->only(['nom', 'type']));

        return redirect()->route('categories.index')->with('success', 'Catégorie modifiée avec succès !');
    }
}
