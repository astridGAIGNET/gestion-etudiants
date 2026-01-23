@extends('layouts.admin')
@section('title', 'Modifier un étudiant')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="mb-3">
                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

            {{--
                 x-data : C'est comme créer un objet JavaScript
                Tout ce qui est entre { } est du JavaScript
            --}}
            <!--  Configuration du composant pour Student -->
            <div class="card" x-data="autoSave({
                id: {{ $student->id }},
                endpoint: '/admin/students',
                fields: ['firstname', 'lastname', 'email', 'birthdate', 'classe_id']
            })">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-pencil-square"></i> Modifier l'étudiant</h2>

                    {{--
                        x-show : Affiche l'élément SI la condition est vraie
                        C'est comme un if() en JavaScript
                    --}}
                    <span x-show="status === 'saving'" class="badge bg-warning">Sauvegarde...</span>
                    <span x-show="status === 'saved'" class="badge bg-success">Sauvegardé ✓</span>
                    <span x-show="status === 'error'" class="badge bg-danger" x-text="errorMessage"></span>
                </div>

                <div class="card-body">
                    {{-- Spinner affiché pendant le chargement --}}
                    <div x-show="loading" class="text-center py-4">
                        <div class="spinner-border"></div>
                    </div>

                    {{--
                        Formulaire affiché quand loading = false
                        @submit.prevent : Empêche le formulaire de se soumettre normalement
                        $el.submit() : Soumet le formulaire de manière classique
                    --}}
                    <form x-show="!loading"
                          action="{{ route('admin.students.update', $student) }}"
                          method="POST"
                          @submit.prevent="$el.submit()">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Prénom *</label>
                            {{--
                                x-model="form.firstname" :
                                - Affiche form.firstname dans l'input
                                - Quand tu tapes, met à jour form.firstname automatiquement

                                @input.debounce.500ms="save()" :
                                - Quand tu tapes, attend 500ms
                                - Si tu continues à taper, réinitialise le compteur
                                - Après 500ms sans taper, appelle save()
                            --}}
                            <input type="text"
                                   class="form-control"
                                   name="firstname"
                                   x-model="form.firstname"
                                   @input.debounce.750ms="save()"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nom *</label>
                            <input type="text"
                                   class="form-control"
                                   name="lastname"
                                   x-model="form.lastname"
                                   @input.debounce.750ms="save()"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email"
                                   class="form-control"
                                   name="email"
                                   x-model="form.email"
                                   @input.debounce.750ms="save()"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date de naissance *</label>
                            {{--
                                @change au lieu de @input car pour les dates
                                on veut sauver quand la date est complète
                            --}}
                            <input type="date"
                                   class="form-control"
                                   name="birthdate"
                                   x-model="form.birthdate"
                                   @change="save()"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Classe</label>
                            <select class="form-select"
                                    name="classe_id"
                                    x-model="form.classe_id"
                                    @change="save()">
                                <option value="">-- Sélectionner une classe --</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}">{{ $classe->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <p> <i class="bi bi-info-circle"></i> La mise à jour se fait automatiquement</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
