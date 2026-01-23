@extends('layouts.front')
@section('title', $student->full_name)

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="mb-3">
                <a href="{{ route('front.students.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour à la liste
                </a>
            </div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">
                        <i class="bi bi-person-circle"></i> {{ $student->full_name }}
                    </h2>
                </div>
                <div class="card-body">
                    <dl class="row">
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
                                <span class="badge bg-secondary">Aucune classe</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">Lieu de formation :</dt>
                        <dd class="col-sm-8">
                            @if($student->classe)
                                <span class="badge bg-success">{{ $student->classe->place->name }}</span>
                            @else
                                <span class="badge bg-secondary">Aucun lieu</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
