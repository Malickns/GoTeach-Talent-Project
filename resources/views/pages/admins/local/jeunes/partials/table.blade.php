<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Jeune</th>
                <th class="d-none d-md-table-cell">Contact</th>
                <th>Niveau</th>
                <th class="d-none d-lg-table-cell">Profil</th>
                <th class="d-none d-xl-table-cell">Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jeunes as $j)
                @php
                    $userStatut = $j->user?->statut;
                    // Met en avant les comptes non validés
                    $highlight = $userStatut !== 'actif';
                    $inscritLe = optional($j->user?->created_at)->format('d/m/Y') ?? optional($j->created_at)->format('d/m/Y');
                    $pref = trim((string)($j->preferences_emploi ?? ''));
                @endphp
                <tr class="{{ $highlight ? 'table-warning' : '' }}">
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle {{ $highlight ? 'bg-warning' : 'bg-primary' }} text-white d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                {{ strtoupper(mb_substr($j->user?->prenom ?? 'J',0,1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $j->nom_complet }}</div>
                                <small class="text-muted">Inscrit le {{ $inscritLe ?: '—' }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <div class="small">{{ $j->telephone ?? $j->user?->telephone }}</div>
                        <div class="small text-muted">{{ $j->user?->email }}</div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">{{ $j->niveau_etude }}</span>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <div class="small">{{ $pref !== '' ? $pref : '—' }}</div>
                    </td>
                    <td class="d-none d-xl-table-cell">
                        <span class="badge {{ $userStatut==='actif' ? 'bg-success' : ($userStatut==='inactif' ? 'bg-secondary' : ($userStatut==='suspendu' ? 'bg-warning text-dark' : 'bg-danger')) }}">{{ $userStatut }}</span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.local.jeunes.show', $j->jeune_id) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                            <a href="{{ route('admin.local.jeunes.edit', $j->jeune_id) }}" class="btn btn-sm btn-outline-secondary">Éditer</a>
                            <div class="btn-group dropstart d-none d-sm-inline">
                                <button type="button" class="btn btn-sm btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Statut
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item js-update-status" data-id="{{ $j->jeune_id }}" data-statut="actif" href="#">Valider (actif)</a></li>
                                    <li><a class="dropdown-item js-update-status" data-id="{{ $j->jeune_id }}" data-statut="inactif" href="#">Inactif</a></li>
                                    <li><a class="dropdown-item js-update-status" data-id="{{ $j->jeune_id }}" data-statut="suspendu" href="#">Suspendre</a></li>
                                    <li><a class="dropdown-item js-update-status" data-id="{{ $j->jeune_id }}" data-statut="supprime" href="#">Supprimer</a></li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Aucun jeune trouvé pour ce programme.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center flex-column flex-md-row gap-2">
    <div class="small text-muted">{{ $jeunes->total() }} résultat(s)</div>
    {{ $jeunes->onEachSide(1)->links() }}
    </div>


