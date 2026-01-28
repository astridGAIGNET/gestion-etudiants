@extends('layouts.admin')
@section('title', 'Gestion des étudiants')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-people-fill"></i> Gestion des étudiants</h1>
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nouvel étudiant
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            {{-- Composant List.js avec Alpine --}}
            <div id="students-list"
                 x-data="listJs({
                     id: 'students-list',
                     valueNames: ['id', 'lastname', 'firstname', 'email', 'classe', 'birthdate'],
                     itemsPerPage: 5
                 })">

                {{-- Barre de recherche et infos --}}
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text"
                                   class="form-control"
                                   placeholder="Rechercher un étudiant..."
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

                {{-- Tableau --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th @click="sortBy('id')" style="cursor: pointer;">
                                ID <i class="bi" :class="getSortIcon('id')"></i>
                            </th>
                            <th @click="sortBy('lastname')" style="cursor: pointer;">
                                Nom <i class="bi" :class="getSortIcon('lastname')"></i>
                            </th>
                            <th @click="sortBy('firstname')" style="cursor: pointer;">
                                Prénom <i class="bi" :class="getSortIcon('firstname')"></i>
                            </th>
                            <th @click="sortBy('email')" style="cursor: pointer;">
                                Email <i class="bi" :class="getSortIcon('email')"></i>
                            </th>
                            <th @click="sortBy('classe')" style="cursor: pointer;">
                                Classe <i class="bi" :class="getSortIcon('classe')"></i>
                            </th>
                            <th>Lieu</th>
                            <th @click="sortBy('birthdate')" style="cursor: pointer;">
                                Naissance <i class="bi" :class="getSortIcon('birthdate')"></i>
                            </th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        @forelse($students as $student)
                            <tr>
                                <td class="id">{{ $student->id }}</td>
                                <td class="lastname">{{ $student->lastname }}</td>
                                <td class="firstname">{{ $student->firstname }}</td>
                                <td class="email">{{ $student->email }}</td>
                                <td class="classe">
                                    @if($student->classe)
                                        {{ $student->classe->name }}
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->classe?->place)
                                        <span class="badge bg-success">{{ $student->classe->place->name }}</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td class="birthdate">{{ $student->birthdate->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.students.show', $student) }}"
                                           class="btn btn-sm btn-info"
                                           title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.students.edit', $student) }}"
                                           class="btn btn-sm btn-warning"
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.students.destroy', $student) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Supprimer cet étudiant ?')">
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
                                <td colspan="8" class="text-center py-4">
                                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <p class="mt-2 text-muted">Aucun étudiant trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination (gérée automatiquement par List.js) --}}
                <nav aria-label="Pagination" class="mt-3">
                    <ul class="pagination pagination-sm justify-content-center" style="cursor: pointer;"></ul>
                </nav>
            </div>
        </div>
    </div>
@endsection
