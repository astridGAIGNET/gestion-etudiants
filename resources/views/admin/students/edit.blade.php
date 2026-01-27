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
                <div class="card-header">
                    <h2><i class="bi bi-pencil-square"></i> Modifier l'étudiant</h2>
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

                        {{-- UTILISATION DU COMPOSANT auto-save --}}
                        <x-auto-save-field
                            name="firstname"
                            label="Prénom"
                            :required="true"
                        />

                        <x-auto-save-field
                            name="lastname"
                            label="Nom"
                            :required="true"
                        />

                        <x-auto-save-field
                            name="email"
                            type="email"
                            label="Email"
                            :required="true"
                        />

                        <x-auto-save-field
                            name="birthdate"
                            type="date"
                            label="Date de naissance"
                            :required="true"
                        />

                        <x-auto-save-field
                            name="classe_id"
                            label="Classe"
                            type="tom-select"
                            :options="$classes"
                            optionValue="id"
                            optionLabel="name"
                            placeholder="Rechercher une classe..."
                            :required="true"
                        />

                        <div class="d-flex justify-content-between">
                            <p> <i class="bi bi-info-circle"></i> La mise à jour se fait automatiquement</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
