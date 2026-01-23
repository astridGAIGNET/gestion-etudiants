@extends('layouts.admin')
@section('title', 'Gestion des lieux de formation')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-geo-alt"></i> Gestion des lieux de formation</h1>
        <a href="{{ route('admin.places.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nouveau lieu de formation
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
                        <th>Nombre de classes</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($places as $place)
                        <tr>
                            <td>{{ $place->id }}</td>
                            <td><strong>{{ $place->name }}</strong></td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $place->classes->count() }} classes
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.places.edit', $place) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.places.destroy', $place) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Supprimer ce lieu de formation ?')">
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
                                <p class="mt-2">Aucun lieu de formation</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $places->links() }}
            </div>
        </div>
    </div>
@endsection
