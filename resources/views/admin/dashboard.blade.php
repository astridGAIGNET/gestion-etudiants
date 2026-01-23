@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
    <h1 class="mb-4">
        <i class="bi bi-speedometer2"></i> Tableau de bord
    </h1>

    <div class="row g-4">
        @if(auth()->user()->isAdmin() || auth()->user()->isFormateur())
            <div class="col-md-4">
                <div class="card text-white bg-primary h-100">
                    <div class="card-body">
                        <h5><i class="bi bi-people-fill"></i> Étudiants</h5>
                        <p class="mb-0">Gérer les étudiants de l'établissement</p>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-light mt-3">
                            Accéder <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        @if(auth()->user()->isAdmin())
            <div class="col-md-4">
                <div class="card text-white bg-success h-100">
                    <div class="card-body">
                        <h5><i class="bi bi-diagram-3-fill"></i> Classes</h5>
                        <p class="mb-0">Gérer les classes et groupes</p>
                        <a href="{{ route('admin.classes.index') }}" class="btn btn-light mt-3">
                            Accéder <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-info h-100">
                    <div class="card-body">
                        <h5><i class="bi bi-person-badge-fill"></i> Formateurs</h5>
                        <p class="mb-0">Gérer les comptes formateurs</p>
                        <a href="{{ route('admin.formateurs.index') }}" class="btn btn-light mt-3">
                            Accéder <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-white bg-warning h-100">
                    <div class="card-body">
                        <h5><i class="bi bi-geo-alt"></i> Lieux de formations</h5>
                        <p class="mb-0">Gérer les lieux de formations</p>
                        <a href="{{ route('admin.places.index') }}" class="btn btn-light mt-3">
                            Accéder <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Bienvenue</h5>
                </div>
                <div class="card-body">
                    <p>Bienvenue sur l'interface d'administration, <strong>{{ auth()->user()->name }}</strong>.</p>
                    <p class="mb-0">
                        Rôle : <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
