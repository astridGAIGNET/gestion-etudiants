@extends('layouts.admin')
@section('title', 'Créer une classe')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="mb-3">
                <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2><i class="bi bi-plus-circle"></i> Créer une classe</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.classes.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom de la classe *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}"
                                   placeholder="Ex: BTS SIO 2024-2025" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <x-quill
                            name="description"
                            label="Description"
                            :height="200"
                        />

                        <x-tom-select
                            name="formateur_id"
                            label="Formateur"
                            :options="$formateurs"
                            :selected="$classes->formateur_id ?? null"
                        />

                        <x-tom-select
                            name="place_id"
                            label="Lieu de formation"
                            :options="$places"
                            :selected="$classes->place_id ?? null"
                        />

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Créer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
