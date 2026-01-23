<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class FormateurController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $formateurs = User::where('role', 'formateur')
            ->withCount('classes')
            ->with('places')
            ->latest()
            ->paginate(15);

        return view('admin.formateurs.index', compact('formateurs'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('admin.formateurs.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'formateur',
        ]);

        return redirect()->route('admin.formateurs.index')
            ->with('success', 'Formateur créé avec succès.');
    }

    public function edit(User $formateur)
    {
        if (!Auth::user()->isAdmin() || $formateur->role !== 'formateur') {
            abort(403);
        }

        return view('admin.formateurs.edit', compact('formateur'));
    }

    public function update(Request $request, User $formateur)
    {
        if (!Auth::user()->isAdmin() || $formateur->role !== 'formateur') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $formateur->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $formateur->name = $validated['name'];
        $formateur->email = $validated['email'];

        if (!empty($validated['password'])) {
            $formateur->password = Hash::make($validated['password']);
        }

        $formateur->save();

        return redirect()->route('admin.formateurs.index')
            ->with('success', 'Formateur mis à jour avec succès.');
    }

    public function destroy(User $formateur)
    {
        if (!Auth::user()->isAdmin() || $formateur->role !== 'formateur') {
            abort(403);
        }

        $formateur->delete();

        return redirect()->route('admin.formateurs.index')
            ->with('success', 'Formateur supprimé avec succès.');
    }
}
