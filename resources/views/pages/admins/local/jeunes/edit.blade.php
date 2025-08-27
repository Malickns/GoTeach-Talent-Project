@extends('pages.admins.local.layout.app')

@section('title', 'Éditer un jeune – Admin local')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded p-3 p-md-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Éditer: {{ $jeune->nom_complet }}</h5>
            <a href="{{ route('admin.local.jeunes.show', $jeune) }}" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left me-1"></i>Retour</a>
        </div>

        @include('pages.admins.local.jeunes.partials.form', [
            'method' => 'PUT',
            'action' => route('admin.local.jeunes.update', $jeune),
            'jeune' => $jeune,
        ])
    </div>
</div>
@endsection


