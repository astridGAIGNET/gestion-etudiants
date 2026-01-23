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

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3"
                                      placeholder="Description optionnelle">{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formateur_id" class="form-label">Formateur responsable *</label>
                            <select class="form-select @error('formateur_id') is-invalid @enderror"
                                    id="formateur_id" name="formateur_id" required>
                                <option value="">-- Sélectionner un formateur --</option>
                                @foreach($formateurs as $formateur)
                                    <option value="{{ $formateur->id }}"
                                        {{ old('formateur_id') == $formateur->id ? 'selected' : '' }}>
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
                                    id="place_id" name="place_id" required>
                                <option value="">-- Sélectionner un lieu de formation --</option>
                                @foreach($places as $place)
                                    <option value="{{ $place->id }}"
                                        {{ old('place_id') == $place->id ? 'selected' : '' }}>
                                        {{ $place->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('place_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

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
