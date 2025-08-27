@extends('pages.admins.national.layout.app')

@section('title', 'Créer un jeune – Admin national')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded p-3 p-md-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Nouveau jeune</h5>
            <a href="{{ route('admin.national.jeunes.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left me-1"></i>Retour</a>
        </div>

        @include('pages.admins.national.jeunes.partials.form', [
            'method' => 'POST',
            'action' => '#',
            'jeune' => null,
        ])
    </div>
  </div>
@endsection


