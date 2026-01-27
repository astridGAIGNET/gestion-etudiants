<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Route::macro('resourceAutoSave', function ($name, $controller, array $options = []) {
            // 1. Crée les routes CRUD
            $resource = Route::resource($name, $controller, $options);

            // 2. Détermine le nom du paramètre
            // Si 'parameters' est défini dans $options (ex: ['parameters' => ['classes' => 'classe']])
            if (!empty($options['parameters'])) {
                // Récupère le premier paramètre custom (ex: 'classe')
                $parameterName = array_values($options['parameters'])[0];
            } else {
                // Sinon, enlève le 's' final (students → student)
                $parameterName = rtrim($name, 's');
            }

            // 3. Ajoute les routes auto-save
            Route::get("{$name}/{{$parameterName}}/data", [$controller, 'getData'])
                ->name("{$name}.data");

            Route::post("{$name}/{{$parameterName}}/auto-save", [$controller, 'autoSave'])
                ->name("{$name}.auto-save");

            // 4. Retourne la ressource pour permettre le chaînage
            return $resource;
        });
    }
}
