@extends('layouts.admin')
@section('title', $student->full_name)

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="mb-3">
                <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
                <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0"><i class="bi bi-person-circle"></i> {{ $student->full_name }}</h2>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">ID :</dt>
                        <dd class="col-sm-8">{{ $student->id }}</dd>

                        <dt class="col-sm-4">Prénom :</dt>
                        <dd class="col-sm-8">{{ $student->firstname }}</dd>

                        <dt class="col-sm-4">Nom :</dt>
                        <dd class="col-sm-8">{{ $student->lastname }}</dd>

                        <dt class="col-sm-4">Email :</dt>
                        <dd class="col-sm-8">{{ $student->email }}</dd>

                        <dt class="col-sm-4">Date de naissance :</dt>
                        <dd class="col-sm-8">{{ $student->birthdate->format('d/m/Y') }}</dd>

                        <dt class="col-sm-4">Classe :</dt>
                        <dd class="col-sm-8">
                            @if($student->classe)
                                <span class="badge bg-primary">{{ $student->classe->name }}</span>
                            @else
                                <span class="badge bg-secondary">Aucune</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Lieu de formation :</dt>
                        <dd class="col-sm-8">
                            @if($student->classe)
                                <span class="badge bg-success">{{ $student->classe->place->name }}</span>
                            @else
                                <span class="badge bg-secondary">Aucune</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Inscrit le :</dt>
                        <dd class="col-sm-8">{{ $student->created_at->format('d/m/Y à H:i') }}</dd>
                    </dl>
                </div>
                <div class="card-footer">
                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Supprimer cet étudiant ?')">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
