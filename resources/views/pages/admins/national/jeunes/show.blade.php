@extends('pages.admins.national.layout.app')

@section('title', 'Détail jeune – Admin national')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded p-3 p-md-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Profil de {{ $jeune->nom_complet }}</h5>
            <a href="{{ route('admin.national.jeunes.index') }}" class="btn btn-sm btn-outline-secondary">Retour</a>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-4">
                <div class="bg-white border rounded p-3 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                            {{ strtoupper(mb_substr($jeune->user?->prenom ?? 'J',0,1)) }}
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $jeune->nom_complet }}</div>
                            <div class="small text-muted">{{ $jeune->user?->email }}</div>
                            <div class="small">{{ $jeune->telephone }}</div>
                        </div>
                    </div>
                    <div class="mb-2"><span class="text-muted">Programme:</span> {{ $jeune->programme?->nom ?? '—' }}</div>
                    <div class="mb-2"><span class="text-muted">Niveau:</span> {{ $jeune->niveau_etude }}</div>
                    <div class="mb-2"><span class="text-muted">Localisation:</span> {{ $jeune->ville }}, {{ $jeune->region }}</div>
                    <div class="mb-2"><span class="text-muted">Disponibilité:</span> {{ $jeune->disponibilite ?? '—' }}</div>
                    <div class="mb-2"><span class="text-muted">Âge:</span> {{ $jeune->age ?? '—' }}</div>

                    <hr>
                    <div class="d-grid gap-2">
                        @if($cv)
                            <a class="btn btn-outline-primary" href="{{ route('admin.national.documents.telecharger', $cv->document_id) }}">
                                <i class="fa fa-file-alt me-1"></i> Télécharger CV
                            </a>
                        @endif
                        @if($lettreMotivation)
                            <a class="btn btn-outline-secondary" href="{{ route('admin.national.documents.telecharger', $lettreMotivation->document_id) }}">
                                <i class="fa fa-envelope me-1"></i> Lettre de motivation
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="bg-white border rounded p-3 mb-3">
                    <h6 class="mb-3">Documents</h6>
                    <div class="row g-2">
                        @forelse($jeune->documents as $doc)
                            <div class="col-12 col-md-6">
                                <div class="border rounded p-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold">{{ $doc->typeLisible }}</div>
                                        <div class="small text-muted">{{ $doc->nom_original }}</div>
                                    </div>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.national.documents.telecharger', $doc->document_id) }}">Télécharger</a>
                                </div>
                            </div>
                        @empty
                            <div class="col-12"><div class="text-muted small">Aucun document.</div></div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white border rounded p-3">
                    <h6 class="mb-3">Candidatures récentes</h6>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Offre</th>
                                    <th>Employeur</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jeune->postulations->sortByDesc('created_at')->take(10) as $p)
                                    <tr>
                                        <td>{{ $p->offreEmplois?->titre }}</td>
                                        <td>{{ $p->offreEmplois?->employeur?->user?->nom_complet ?? $p->offreEmplois?->employeur?->nom_entreprise }}</td>
                                        <td><span class="badge bg-light text-dark">{{ $p->statut }}</span></td>
                                        <td class="small text-muted">{{ $p->created_at?->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-muted">Aucune candidature.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


