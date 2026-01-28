<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Charge la classe ET le lieu
            $students = Student::with('classe.place')->latest()->get();
        } elseif ($user->isFormateur()) {
            $classIds = $user->classes->pluck('id');
            $students = Student::whereIn('classe_id', $classIds)
                ->with('classe.place') // -> la classe ET son lieu
                ->latest()
                ->get();
        } else {
            abort(403);
        }

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $classes = Classe::all();
        } elseif ($user->isFormateur()) {
            $classes = $user->classes;
        } else {
            abort(403);
        }

        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'birthdate' => 'required|date|before:today',
            'classe_id' => 'nullable|exists:classes,id',
        ]);

        $user = Auth::user();
        if ($user->isFormateur() && $validated['classe_id']) {
            if (!$user->classes->contains($validated['classe_id'])) {
                abort(403);
            }
        }

        Student::create($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Étudiant créé avec succès.');
    }

    public function show(Student $student)
    {
        $this->authorizeStudentAccess($student);
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $this->authorizeStudentAccess($student);

        $user = Auth::user();
        if ($user->isAdmin()) {
            $classes = Classe::all();
        } else {
            $classes = $user->classes;
        }

        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $this->authorizeStudentAccess($student);

        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'birthdate' => 'required|date|before:today',
            'classe_id' => 'nullable|exists:classes,id',
        ]);

        $user = Auth::user();
        if ($user->isFormateur() && $validated['classe_id']) {
            if (!$user->classes->contains($validated['classe_id'])) {
                abort(403);
            }
        }

        $student->update($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Étudiant mis à jour avec succès.');
    }

    public function destroy(Student $student)
    {
        $this->authorizeStudentAccess($student);
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Étudiant supprimé avec succès.');
    }

    private function authorizeStudentAccess(Student $student)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return;
        }

        if ($user->isFormateur()) {
            $classIds = $user->classes->pluck('id');
            if (!$classIds->contains($student->classe_id)) {
                abort(403);
            }
        } else {
            abort(403);
        }
    }

    /**
     * Récupère les données de l'étudiant en JSON
     */
    public function getData(Student $student)
    {
        $this->authorizeStudentAccess($student);

        return response()->json([
            'success' => true,
            'data' => [
                'firstname' => $student->firstname ?? '',
                'lastname' => $student->lastname ?? '',
                'email' => $student->email ?? '',
                'birthdate' => $student->birthdate ? $student->birthdate->format('Y-m-d') : '',
                'classe_id' => $student->classe_id ?? ''
            ]
        ]);
    }

    /**
     * Sauvegarde automatique
     */
    public function autoSave(Request $request, Student $student)
    {
        $this->authorizeStudentAccess($student);

        // Validation (nullable car on envoie champ par champ)
        $validated = $request->validate([
            'firstname' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:students,email,' . $student->id,
            'birthdate' => 'nullable|date|before:today',
            'classe_id' => 'nullable|exists:classes,id',
        ]);

        // Vérification pour les formateurs
        $user = Auth::user();
        if ($user->isFormateur() && isset($validated['classe_id']) && $validated['classe_id']) {
            if (!$user->classes->contains($validated['classe_id'])) {
                return response()->json(['success' => false, 'message' => 'Classe non autorisée'], 403);
            }
        }

        // Mise à jour (on enlève les valeurs null)
        $student->update(array_filter($validated, function($value) {
            return $value !== null;
        }));

        return response()->json([
            'success' => true,
            'message' => 'Sauvegardé'
        ]);
    }
}
