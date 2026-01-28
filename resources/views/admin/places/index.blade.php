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

            <div id="places-list"
                 x-data="listJs({
                    id: 'places-list',
                    valueNames: ['id', 'name', 'classe'],
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
                            <th @click="sortBy('classe')" style="cursor: pointer;">
                                Nombre de classes <i class="bi" :class="getSortIcon('classe')"></i>
                            </th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        @forelse($places as $place)
                            <tr>
                                <td class="id">{{ $place->id }}</td>
                                <td class="name"><strong>{{ $place->name }}</strong></td>
                                <td class="classe">
                                    <span class="badge bg-primary">
                                        {{ $place->classes->count() }} classes
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.places.edit', $place) }}" class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.places.destroy', $place) }}" method="POST" class="d-inline" onsubmit="return confirm ('Supprimer ce lieu de formation ?')">
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
                                    <p class="mt-2 text-muted">Aucun lieu de formation trouvé</p>
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
