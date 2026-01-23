@extends('layouts.front')
@section('title', 'Liste des étudiants')

@section('content')
    <h1 class="mb-4">
        <i class="bi bi-people-fill"></i> Liste des étudiants
    </h1>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Classe</th>
                        <th>Lieu de formation</th>
                        <th>Date de naissance</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $student->lastname }}</td>
                            <td>{{ $student->firstname }}</td>
                            <td>{{ $student->email }}</td>
                            <td>
                                @if($student->classe)
                                    <span class="badge bg-primary">{{ $student->classe->name }}</span>
                                @else
                                    <span class="badge bg-secondary">Aucune</span>
                                @endif
                            </td>
                            <td>
                                @if($student->classe)
                                    <span class="badge bg-success">{{ $student->classe->place->name }}</span>
                                @else
                                    <span class="badge bg-secondary">Aucune</span>
                                @endif
                            </td>
                            <td>{{ $student->birthdate->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('front.students.show', $student) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> Voir
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="mt-2 text-muted">Aucun étudiant inscrit</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $students->links() }}
            </div>
        </div>
    </div>
@endsection
