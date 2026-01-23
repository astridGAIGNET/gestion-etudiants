@extends('layouts.admin')
@section('title', 'Gestion des classes')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-diagram-3-fill"></i> Gestion des classes</h1>
        <a href="{{ route('admin.classes.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nouvelle classe
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Lieu de formation</th>
                        <th>Formateur</th>
                        <th>Nb étudiants</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($classes as $classe)
                        <tr>
                            <td>{{ $classe->id }}</td>
                            <td><strong>{{ $classe->name }}</strong></td>
                            <td>{{ Str::limit($classe->description ?? '-', 50) }}</td>
                            <td>
                                <span class="badge bg-success">
                                    {{ $classe->place->name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    <i class="bi bi-person-badge"></i> {{ $classe->formateur->name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $classe->students->count() }} étudiants
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.classes.edit', $classe) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.classes.destroy', $classe) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Supprimer cette classe ?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-2">Aucune classe</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $classes->links() }}
            </div>
        </div>
    </div>
@endsection
