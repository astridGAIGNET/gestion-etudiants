@extends('layouts.admin')
@section('title', 'Modifier un lieu de formation')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="mb-3">
                <a href="{{ route('admin.places.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>

            {{--
                 x-data : C'est comme créer un objet JavaScript
                Tout ce qui est entre { } est du JavaScript
            --}}
            <!--  Configuration du composant pour Student -->
            <div class="card" x-data="autoSave({
                id: {{ $place->id }},
                endpoint: '/admin/places',
                fields: ['name']
            })">
                <div class="card-header">
                    <h2><i class="bi bi-pencil-square"></i> Modifier le lieu de formation</h2>
                </div>
                <div class="card-body">
                    <div x-show="loading" class="text-center py-4">
                        <div class="spinner-border"></div>
                    </div>
                    <form x-show="!loading"
                          action="{{ route('admin.places.update', $place) }}"
                          method="POST"
                          @submit.prevent="$el.submit()">
                        @csrf
                        @method('PUT')

                        {{-- UTILISATION DU COMPOSANT auto-save --}}
                        <x-auto-save-field
                            name="name"
                            label="Nom du lieu de formation"
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
