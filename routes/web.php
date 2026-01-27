<?php

use App\Http\Controllers\Admin\PlaceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\ClasseController;
use App\Http\Controllers\Admin\FormateurController;
use App\Http\Controllers\Front\StudentController as FrontStudentController;

// Page d'accueil redirige vers la liste publique des étudiants
Route::get('/', function () {
    return redirect()->route('front.students.index');
});

// ========================================
// Routes Front (publiques)
// ========================================
Route::prefix('students')->name('front.students.')->group(function () {
    Route::get('/', [FrontStudentController::class, 'index'])->name('index');
    Route::get('/{student}', [FrontStudentController::class, 'show'])->name('show');
});

// ========================================
// Routes Admin (protégées)
// ========================================
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Gestion des étudiants (Admin + Formateur)
    Route::resourceAutoSave('students', AdminStudentController::class);

    // Gestion des classes (Admin uniquement)
    Route::resourceAutoSave('classes', ClasseController::class, [
        'parameters' => ['classes' => 'classe']
    ]);

    // Gestion des formateurs (Admin uniquement)
    Route::resourceAutoSave('formateurs', FormateurController::class);

    // Gestion des lieux (Admin uniquement)
    Route::resourceAutoSave('places', PlaceController::class);

});
