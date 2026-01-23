@extends('layouts.admin')
@section('title', 'Modifier une classe')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="mb-3">
                <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

            <div class="card" x-data="autoSave({
                id: {{ $classe->id }},
                endpoint: '/admin/classes',
                fields: ['name', 'description', 'formateur_id', 'place_id']
            })"
            @quill-change="save()">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-pencil-square"></i> Modifier la classe</h2>
                    <span x-show="status === 'saving'" class="badge bg-warning">Sauvegarde...</span>
                    <span x-show="status === 'saved'" class="badge bg-success">Sauvegardé ✓</span>
                    <span x-show="status === 'error'" class="badge bg-danger" x-text="errorMessage"></span>
                </div>

                <div class="card-body">
                    {{-- Spinner affiché pendant le chargement --}}
                    <div x-show="loading" class="text-center py-4">
                        <div class="spinner-border"></div>
                    </div>
                    <form x-show="!loading"
                          action="{{ route('admin.classes.update', $classe) }}"
                          method="POST" @submit.prevent="$el.submit()">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Nom de la classe *</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   name="name"
                                   x-model="form.name"
                                   @input.debounce.750ms="save()" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <x-quill id="description" model="form.description" :height="300"></x-quill>
                            @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formateur_id" class="form-label">Formateur responsable *</label>
                            <select class="form-select @error('formateur_id') is-invalid @enderror"
                                    name="formateur_id"
                                    x-model="form.formateur_id" @change="save()" required>
                                <option value="">-- Sélectionner un formateur --</option>
                                @foreach($formateurs as $formateur)
                                    <option value="{{ $formateur->id }}">
                                        {{ $formateur->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('formateur_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="place_id" class="form-label">Lieu de formation *</label>
                            <select class="form-select @error('place_id') is-invalid @enderror"
                                    name="place_id"
                                    x-model="form.place_id" @change="save()" required>
                                <option value="">-- Sélectionner un lieu de formation --</option>
                                @foreach($places as $place)
                                    <option value="{{ $place->id }}">
                                        {{ $place->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('place_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
