@extends('layouts.admin')
@section('title', 'Modifier un formateur')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="mb-3">
                <a href="{{ route('admin.formateurs.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

            <div class="card" x-data="{
                ...autoSave({
                    id: {{ $formateur->id }},
                    endpoint: '/admin/formateurs',
                    fields: ['name', 'email']
                }),
            }">
                <div class="card-header">
                    <h2><i class="bi bi-pencil-square"></i> Modifier le formateur</h2>
                </div>

                <div class="card-body">
                    <div x-show="loading" class="text-center py-4">
                        <div class="spinner-border"></div>
                    </div>

                    <form x-show="!loading"
                          action="{{ route('admin.formateurs.update', $formateur) }}"
                          method="POST"
                          @submit.prevent="$el.submit()">
                        @csrf
                        @method('PUT')

                        {{-- UTILISATION DU COMPOSANT auto-save --}}
                        <x-auto-save-field
                            name="name"
                            label="Nom complet"
                            :required="true"
                            placeholder="Entrez le nom complet"
                        />

                        <x-auto-save-field
                            name="email"
                            label="Adresse email"
                            type="email"
                            :required="true"
                            placeholder="exemple@email.com"
                        />

                        <hr class="my-4">

                        <h5 class="mb-3"><i class="bi bi-key"></i> Modifier le mot de passe (optionnel)</h5>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            Laissez les champs vides si vous ne voulez pas changer le mot de passe.
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password">
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer</label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.formateurs.index') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-info">
                                <i class="bi bi-check-circle"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
