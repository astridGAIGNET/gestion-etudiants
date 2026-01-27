<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class PlaceController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $places = Place::with(['classes'])->latest()->paginate(15);
        return view('admin.places.index', compact('places'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $classes = Classe::where('id')->get();
        return view('admin.places.create', compact('classes'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Place::create($validated);
        return redirect()->route('admin.places.index')->with('success', 'Lieu créé avec succès.');
    }

    public function edit(Place $place)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $classes = Classe::where('id')->get();
        return view('admin.places.edit', compact('place', 'classes'));
    }

    public function update(Request $request, Place $place)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $place->update($validated);

        return redirect()->route('admin.places.index')->with('success', 'Lieu mis à jour avec succès');
    }

    public function destroy(Place $place)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $place->delete();

        return redirect()->route('admin.places.index')->with('success', 'Lieu supprimé avec succès');
    }

    /**
     * Récupère les données de la classe en JSON
     */
    public function getData(Place $place)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'name' => $place->name ?? '',
            ]
        ]);
    }

    /**
     * Sauvegarde automatique
     */
    public function autoSave(Request $request, Place $place)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // Validation (nullable car on envoie champ par champ)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Mise à jour (on enlève les valeurs null)
        $place->update(array_filter($validated, function($value) {
            return $value !== null;
        }));

        return response()->json([
            'success' => true,
            'message' => 'Sauvegardé'
        ]);
    }
}
