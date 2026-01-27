@extends('layouts.admin')
@section('title', 'Modifier une classe')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="mb-3">
                <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

            <div class="card" x-data="autoSave({
                id: {{ $classe->id }},
                endpoint: '/admin/classes',
                fields: ['name', 'description', 'formateur_id', 'place_id']
            })"
            @quill-change="save()">
                <div class="card-header">
                    <h2><i class="bi bi-pencil-square"></i> Modifier la classe</h2>
                </div>

                <div class="card-body">
                    {{-- Spinner affiché pendant le chargement --}}
                    <div x-show="loading" class="text-center py-4">
                        <div class="spinner-border"></div>
                    </div>
                    <form x-show="!loading"
                          action="{{ route('admin.classes.update', $classe) }}"
                          method="POST" @submit.prevent="$el.submit()">
                        @csrf
                        @method('PUT')

                        {{-- UTILISATION DU COMPOSANT auto-save --}}
                        <x-auto-save-field
                            name="name"
                            label="Nom de la classe"
                            :required="true"
                        />

                        <x-auto-save-field
                            name="description"
                            label="Description"
                            type="quill"
                            :height="400"
                            :required="true"
                        />

                        <x-auto-save-field
                            name="formateur_id"
                            label="Formateur responsable"
                            type="tom-select"
                            :options="$formateurs"
                            optionValue="id"
                            optionLabel="name"
                            placeholder="Rechercher un formateur..."
                            :required="true"
                        />

                        <x-auto-save-field
                            name="place_id"
                            label="Lieu de formation"
                            type="tom-select"
                            :options="$places"
                            optionValue="id"
                            optionLabel="name"
                            placeholder="-- Sélectionner un lieu de formation --"
                            :required="true"
                        />

                        <div class="d-flex justify-content-between">
                            <p> <i class="bi bi-info-circle"></i> La mise à jour se fait automatiquement</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
