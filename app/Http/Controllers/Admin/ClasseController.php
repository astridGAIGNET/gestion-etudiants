<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Place;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClasseController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $classes = Classe::with(['formateur', 'students'])->latest()->paginate(15);
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $formateurs = User::where('role', 'formateur')->get();
        $places = Place::all();
        return view('admin.classes.create', compact('formateurs', 'places'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'formateur_id' => 'required|exists:users,id',
            'place_id' => 'required|exists:places,id',
        ]);

        Classe::create($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Classe créée avec succès.');
    }

    public function edit(Classe $classe)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $formateurs = User::where('role', 'formateur')->get();
        $places = Place::all();
        return view('admin.classes.edit', compact('classe', 'formateurs', 'places'));
    }

    public function update(Request $request, Classe $classe)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'formateur_id' => 'required|exists:users,id',
            'place_id' => 'required|exists:places,id',
        ]);

        $classe->update($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Classe mise à jour avec succès.');
    }

    public function destroy(Classe $classe)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $classe->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Classe supprimée avec succès.');
    }

    /**
     * Récupère les données de la classe en JSON
     */
    public function getData(Classe $classe)
    {
        return response()->json([
            'success' => true,
            'classe' => [
                'name' => $classe->name ?? '',
                'description' => $classe->description ?? '',
                'formateur_id' => $classe->formateur_id ?? '',
                'place_id' => $classe->place_id ?? '',
            ]
        ]);
    }

    /**
     * Sauvegarde automatique
     */
    public function autoSave(Request $request, Classe $classe)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // Validation (nullable car on envoie champ par champ)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'formateur_id' => 'required|exists:users,id',
            'place_id' => 'required|exists:places,id',
        ]);

        // Mise à jour (on enlève les valeurs null)
        $classe->update(array_filter($validated, function($value) {
            return $value !== null;
        }));

        return response()->json([
            'success' => true,
            'message' => 'Sauvegardé ✓'
        ]);
    }
}
