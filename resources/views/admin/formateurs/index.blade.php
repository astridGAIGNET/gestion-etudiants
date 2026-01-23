@extends('layouts.admin')
@section('title', 'Gestion des formateurs')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-person-badge-fill"></i> Gestion des formateurs</h1>
        <a href="{{ route('admin.formateurs.create') }}" class="btn btn-info">
            <i class="bi bi-plus-circle"></i> Nouveau formateur
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
                        <th>Email</th>
                        <th>Nb classes</th>
                        <th>Inscrit le</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($formateurs as $formateur)
                        <tr>
                            <td>{{ $formateur->id }}</td>
                            <td>
                                <strong>{{ $formateur->name }}</strong><br>
                                <span class="badge bg-info">Formateur</span>
                                @foreach($formateur->places as $place)
                                    <span class="badge bg-success">{{ $place->name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $formateur->email }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $formateur->classes_count }} classe(s)
                                </span>
                            </td>
                            <td>{{ $formateur->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.formateurs.edit', $formateur) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.formateurs.destroy', $formateur) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Supprimer ce formateur ?')">
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
                                <p class="mt-2">Aucun formateur</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $formateurs->links() }}
            </div>
        </div>
    </div>
@endsection
