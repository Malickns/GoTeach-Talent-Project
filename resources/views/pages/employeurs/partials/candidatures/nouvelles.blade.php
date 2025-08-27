<div class="content-card">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-user-check"></i>
            Nouvelles candidatures
        </h2>
        <a href="#" class="btn-secondary" onclick="employeurDashboard.showToast('Tous les candidats (à venir)','info'); return false;"><i class="fas fa-users"></i> Voir toutes</a>
    </div>
    <div class="candidates-list" id="new-applications-list" style="gap: 10px;">
        @forelse($recentPostulations as $postulation)
            <?php
                $initials = substr($postulation->jeune?->user?->prenom ?? 'C', 0, 1) . substr($postulation->jeune?->user?->nom ?? 'N', 0, 1);
                $nom = trim(($postulation->jeune?->user?->prenom ?? '') . ' ' . ($postulation->jeune?->user?->nom ?? '')) ?: 'Candidat';
                $offreTitre = $postulation->offreEmplois?->titre ?? 'Offre inconnue';
                $dateStr = optional($postulation->created_at)->diffForHumans();
                $statut = $postulation->statut;
            ?>
            <div class="candidate-item" data-postulation-id="{{ $postulation->postulation_id }}" style="grid-template-columns: 40px 1fr auto;">
                <div class="candidate-avatar" style="width:40px;height:40px;">
                    <i class="fas fa-user" style="font-size:14px;color:var(--accent);"></i>
                </div>
                <div class="candidate-info">
                    <h4 class="candidate-name" style="margin-bottom:2px;">{{ $nom }}</h4>
                    <p class="candidate-skills" style="gap:6px;">
                        <i class="fas fa-briefcase" style="font-size:12px;color:var(--gray);"></i> <span style="color:var(--primary);font-weight:600;">{{ $offreTitre }}</span>
                        <span class="badge {{ in_array($statut, ['en_attente','en_revue']) ? 'badge-primary' : 'badge-secondary' }}" style="text-transform:capitalize;">{{ str_replace('_',' ', $statut) }}</span>
                        <span style="color: var(--gray); font-size:12px;">
                            <i class="far fa-clock"></i> {{ $dateStr }}
                        </span>
                    </p>
                </div>
                <div class="candidate-actions">
                    <button class="btn-sm btn-view" data-action="view-candidature"><i class="fas fa-eye"></i></button>
                </div>
            </div>
        @empty
            <div class="candidate-item" style="justify-content:center;">
                <div class="candidate-info" style="text-align:center;">
                    <h4 class="candidate-name">Aucune nouvelle candidature</h4>
                    <p class="candidate-skills"><i class="fas fa-info-circle"></i> Encouragez les talents en publiant une nouvelle offre.</p>
                </div>
            </div>
        @endforelse
    </div>

    @if(isset($candidatures) && $candidatures instanceof \Illuminate\Contracts\Pagination\Paginator)
        <div style="display:flex; justify-content:flex-end; margin-top:10px;">
            {!! $candidatures->withQueryString()->links() !!}
        </div>
    @endif
</div>


