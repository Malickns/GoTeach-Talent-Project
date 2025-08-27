<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Jeune</th>
                <th class="d-none d-md-table-cell">Contact</th>
                <th>Programme</th>
                <th class="d-none d-lg-table-cell">Profil</th>
                <th class="d-none d-xl-table-cell">Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jeunes as $j)
                @php
                    $userStatut = $j->user?->statut;
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
                        <span class="badge bg-light text-dark">{{ $j->programme?->nom ?? '—' }}</span>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <div class="small">{{ $pref !== '' ? $pref : '—' }}</div>
                    </td>
                    <td class="d-none d-xl-table-cell">
                        <span class="badge {{ $userStatut==='actif' ? 'bg-success' : ($userStatut==='inactif' ? 'bg-secondary' : ($userStatut==='suspendu' ? 'bg-warning text-dark' : 'bg-danger')) }}">{{ $userStatut }}</span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.national.jeunes.show', $j->jeune_id) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                            @php $cv = $j->documents()->where('type','cv')->first(); @endphp
                            @if($cv)
                                <a href="{{ route('admin.national.documents.telecharger', $cv->document_id) }}" class="btn btn-sm btn-outline-success">CV</a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Aucun jeune trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center flex-column flex-md-row gap-2">
    <div class="small text-muted">{{ $jeunes->total() }} résultat(s)</div>
    {{ $jeunes->onEachSide(1)->links() }}
</div>


