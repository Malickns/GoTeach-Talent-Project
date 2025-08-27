@extends('pages.admins.local.layout.app')

@section('title', 'Détails du jeune – Admin local')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded p-3 p-md-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:50px;height:50px;">
                    {{ strtoupper(mb_substr($jeune->user?->prenom ?? 'J',0,1)) }}
                </div>
                <div>
                    <h5 class="mb-0">{{ $jeune->nom_complet }}</h5>
                    <div class="d-flex align-items-center gap-2">
                        <small class="text-muted">{{ $jeune->niveau_etude }} • {{ $jeune->ville }}, {{ $jeune->region }}</small>
                        <span class="badge {{ $jeune->user?->statut==='actif' ? 'bg-success' : ($jeune->user?->statut==='inactif' ? 'bg-secondary' : ($jeune->user?->statut==='suspendu' ? 'bg-warning text-dark' : 'bg-danger')) }}">
                            {{ $jeune->user?->statut }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.local.jeunes.edit', $jeune) }}" class="btn btn-outline-secondary"><i class="fa fa-edit me-1"></i>Éditer</a>
                <form method="POST" action="{{ route('admin.local.jeunes.destroy', $jeune) }}" onsubmit="return confirm('Supprimer ce jeune ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger"><i class="fa fa-trash me-1"></i>Supprimer</button>
                </form>
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        Changer statut
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item js-update-status" data-id="{{ $jeune->jeune_id }}" data-statut="actif" href="#">Valider (actif)</a></li>
                        <li><a class="dropdown-item js-update-status" data-id="{{ $jeune->jeune_id }}" data-statut="inactif" href="#">Inactif</a></li>
                        <li><a class="dropdown-item js-update-status" data-id="{{ $jeune->jeune_id }}" data-statut="suspendu" href="#">Suspendre</a></li>
                        <li><a class="dropdown-item js-update-status" data-id="{{ $jeune->jeune_id }}" data-statut="supprime" href="#">Supprimer</a></li>
                    </ul>
                </div>
                <a href="{{ route('admin.local.jeunes.index') }}" class="btn btn-primary"><i class="fa fa-list me-1"></i>Liste</a>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-lg-7">
                <div class="bg-white border rounded p-3 h-100">
                    <h6 class="mb-3">Informations personnelles</h6>
                    <div class="row g-2 small">
                        <div class="col-6">Email: <span class="text-muted">{{ $jeune->user?->email }}</span></div>
                        <div class="col-6">Téléphone: <span class="text-muted">{{ $jeune->telephone ?? $jeune->user?->telephone }}</span></div>
                        <div class="col-6">Date de naissance: <span class="text-muted">{{ $jeune->date_naissance?->format('d/m/Y') }}</span></div>
                        <div class="col-6">Genre: <span class="text-muted">{{ ucfirst($jeune->genre) }}</span></div>
                        <div class="col-6">Nationalité: <span class="text-muted">{{ $jeune->nationalite }}</span></div>
                        <div class="col-6">CNI: <span class="text-muted">{{ $jeune->numero_cni ?: '—' }}</span></div>
                        <div class="col-12">Adresse: <span class="text-muted">{{ $jeune->adresse }}</span></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-5">
                <div class="bg-white border rounded p-3 h-100">
                    <h6 class="mb-3">Statistiques</h6>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="fw-bold">{{ $jeune->nombre_candidatures }}</div>
                            <small class="text-muted">Candidatures</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold">{{ $jeune->nombre_entretiens }}</div>
                            <small class="text-muted">Entretiens</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold">{{ $jeune->nombre_emplois_obtenus }}</div>
                            <small class="text-muted">Emplois</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="bg-white border rounded p-3">
                    <h6 class="mb-3">Candidatures récentes</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Offre</th>
                                    <th>Employeur</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jeune->postulations()->latest()->limit(10)->get() as $p)
                                    <tr>
                                        <td>{{ $p->offreEmplois?->titre }}</td>
                                        <td>{{ $p->offreEmplois?->employeur?->nom_entreprise }}</td>
                                        <td><span class="badge bg-light text-dark">{{ str_replace('_',' ', $p->statut) }}</span></td>
                                        <td>{{ $p->created_at?->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted">Aucune candidature</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.querySelectorAll('.js-update-status').forEach(el => {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const statut = this.getAttribute('data-statut');
            fetch(`{{ url('/admins/local/jeunes') }}/${id}/status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': `{{ csrf_token() }}`,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ statut })
            }).then(r => r.json()).then(() => {
                window.location.reload();
            });
        });
    });
</script>
@endpush
@endsection


