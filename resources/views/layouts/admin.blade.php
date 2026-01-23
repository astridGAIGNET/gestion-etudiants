<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ config('app.name') }} - @yield('title')</title>
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    {{-- Scripts supplémentaires poussés depuis les composants --}}
    @stack('head-scripts')
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar-admin">
            <div class="position-sticky">
                <div class="sidebar-header">
                    <h5><i class="bi bi-shield-check"></i> Administration</h5>
                    <small>{{ auth()->user()->name }}</small><br>
                    <small class="badge bg-primary mt-1">{{ ucfirst(auth()->user()->role) }}</small>
                </div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                           href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    @if(auth()->user()->isAdmin() || auth()->user()->isFormateur())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}"
                               href="{{ route('admin.students.index') }}">
                                <i class="bi bi-people"></i> Étudiants
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}"
                               href="{{ route('admin.classes.index') }}">
                                <i class="bi bi-diagram-3"></i> Classes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.formateurs.*') ? 'active' : '' }}"
                               href="{{ route('admin.formateurs.index') }}">
                                <i class="bi bi-person-badge"></i> Formateurs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.places.*') ? 'active' : '' }}"
                               href="{{ route('admin.places.index') }}">
                                <i class="bi bi-geo-alt"></i> Lieux de formations
                            </a>
                        </li>
                    @endif

                    <li class="nav-item mt-3 border-top pt-3">
                        <a class="nav-link" href="{{ route('front.students.index') }}">
                            <i class="bi bi-arrow-left-circle"></i> Retour au site
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.show') }}">
                            <i class="bi bi-person-circle"></i> Mon profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                                <i class="bi bi-box-arrow-right"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>

                <div class="p-3 border-top mt-3">
                    <button class="btn btn-outline-light w-100" id="theme-toggle">
                        <i class="bi bi-moon-fill"></i> Thème
                    </button>
                </div>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
