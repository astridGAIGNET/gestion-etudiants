@extends('layouts.admin')
@section('title', 'Créer un lieu de formation')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="mb-3">
                <a href="{{ route('admin.places.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2><i class="bi bi-plus-circle"></i> Créer un lieu de formation</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.places.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom du lieu *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Nom de la commune" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.places.index') }}" class="btn btn-secondary">Annuler</a>
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
