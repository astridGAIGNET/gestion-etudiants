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

            <div id="formateurs-list"
                 x-data="listJs({
                    id: 'formateurs-list',
                    valueNames: ['id', 'name', 'email', 'classe', 'created_at'],
                    itemsPerPage: 5
                 })">

                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text"
                                   class="form-control"
                                   placeholder="Rechercher un formateur..."
                                   x-model="searchQuery"
                                   @input="search()">
                            <button class="btn btn-outline-secondary"
                                    type="button"
                                    @click="clearSearch()"
                                    x-show="searchQuery.length > 0">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
                        <span class="text-muted">
                            Total : <span class="badge bg-primary" x-text="totalItems"></span>
                        </span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th @click="sortBy('id')" style="cursor: pointer;">
                                ID <i class="bi" :class="getSortIcon('id')"></i>
                            </th>
                            <th @click="sortBy('name')" style="cursor: pointer;">
                                Nom <i class="bi" :class="getSortIcon('name')"></i>
                            </th>
                            <th @click="sortBy('email')" style="cursor: pointer;">
                                Email <i class="bi" :class="getSortIcon('email')"></i>
                            </th>
                            <th @click="sortBy('classe')" style="cursor: pointer;">
                                Nb classes <i class="bi" :class="getSortIcon('classe')"></i>
                            </th>
                            <th @click="sortBy('created_at')" style="cursor: pointer;">
                                Inscrit le <i class="bi" :class="getSortIcon('created_at')"></i>
                            </th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        @forelse($formateurs as $formateur)
                            <tr>
                                <td class="id">{{ $formateur->id }}</td>
                                <td class="name">
                                    <strong>{{ $formateur->name }}</strong><br>
                                    <span class="badge bg-info">Formateur</span>
                                    @foreach($formateur->places as $place)
                                        <span class="badge bg-success">{{ $place->name }}</span>
                                    @endforeach
                                </td>
                                <td class="email">{{ $formateur->email }}</td>
                                <td class="classe">
                                    <span class="badge bg-primary">
                                        {{ $formateur->classes_count }} classe(s)
                                    </span>
                                </td>
                                <td class="created_at">{{ $formateur->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.formateurs.edit', $formateur) }}" class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.formateurs.destroy', $formateur) }}" method="POST" class="d-inline" onsubmit="return confirm ('Supprimer ce formateur ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
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
                                    <p class="mt-2 text-muted">Aucun formateur trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <nav aria-label="Pagination" class="mt-3">
                    <ul class="pagination pagination-sm justify-content-center" style="cursor: pointer;"></ul>
                </nav>
            </div>
        </div>
    </div>
@endsection
