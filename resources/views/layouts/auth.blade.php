<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
<div class="auth-container">
    <div class="auth-card">
        <div class="card shadow-lg">
            <div class="card-body p-4">
                <div class="auth-logo">
                    <i class="bi bi-mortarboard-fill" style="font-size: 3rem;"></i>
                    <h2 class="mt-2">{{ config('app.name') }}</h2>
                </div>

                @yield('content')
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('front.students.index') }}" class="text-white">
                <i class="bi bi-arrow-left"></i> Retour au site
            </a>
        </div>
    </div>
</div>
</body>
</html>
