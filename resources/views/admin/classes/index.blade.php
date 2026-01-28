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

            <div id="classes-list"
                 x-data="listJs({
                    id: 'classes-list',
                    valueNames: ['id', 'name', 'description', 'place', 'formateur', 'student'],
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
                                   placeholder="Rechercher une classe..."
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
                            <th @click="sortBy('id')"
                                style="cursor: pointer;">
                                ID <i class="bi"
                                      :class="getSortIcon('id')"></i>
                            </th>
                            <th @click="sortBy('name')" style="cursor: pointer;">
                                Nom <i class="bi" :class="getSortIcon('name')"></i>
                            </th>
                            <th>Description</th>
                            <th @click="sortBy('place')"
                                style="cursor: pointer;">
                                Lieu de formation <i class="bi" :class="getSortIcon('place')"></i>
                            </th>
                            <th @click="sortBy('formateur')"
                                style="cursor: pointer;">
                                Formateur <i class="bi" :class="getSortIcon('formateur')"></i>
                            </th>
                            <th @click="sortBy('student')"
                                style="cursor: pointer;">
                                Nb étudiants <i class="bi" :class="getSortIcon('student')"></i></th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        @forelse($classes as $classe)
                            <tr>
                                <td class="id">{{ $classe->id }}</td>
                                <td class="name"><strong>{{ $classe->name }}</strong></td>
                                <td class="description">{{ Str::limit($classe->description ?? '-', 40) }}</td>
                                <td class="place">
                                    <span class="badge bg-success">
                                        {{ $classe->place->name }}
                                    </span>
                                </td>
                                <td class="formateur">
                                    <span class="badge bg-info">
                                        <i class="bi bi-person-badge"></i> {{ $classe->formateur->name }}
                                    </span>
                                </td>
                                <td class="student">
                                    <span class="badge bg-primary">
                                        {{ $classe->students->count() }} étudiants
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.classes.edit', $classe) }}"
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.classes.destroy', $classe) }}" method="POST"
                                              class="d-inline" onsubmit="return confirm ('Supprimer cette classe ?')">
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
                                    <p class="mt-2 text-muted">Aucune classe trouvée</p>
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
