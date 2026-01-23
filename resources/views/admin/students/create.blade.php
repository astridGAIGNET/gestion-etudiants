@extends('layouts.admin')
@section('title', 'Créer un étudiant')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="mb-3">
                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2><i class="bi bi-person-plus"></i> Créer un étudiant</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.students.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="firstname" class="form-label">Prénom *</label>
                            <input type="text" class="form-control @error('firstname') is-invalid @enderror"
                                   id="firstname" name="firstname" value="{{ old('firstname') }}" required>
                            @error('firstname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="lastname" class="form-label">Nom *</label>
                            <input type="text" class="form-control @error('lastname') is-invalid @enderror"
                                   id="lastname" name="lastname" value="{{ old('lastname') }}" required>
                            @error('lastname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Date de naissance *</label>
                            <input type="date" class="form-control @error('birthdate') is-invalid @enderror"
                                   id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                            @error('birthdate')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="classe_id" class="form-label">Classe</label>
                            <select class="form-select @error('classe_id') is-invalid @enderror"
                                    id="classe_id" name="classe_id">
                                <option value="">Aucune classe</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                        {{ $classe->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('classe_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Créer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
