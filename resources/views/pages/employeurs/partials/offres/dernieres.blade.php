<div class="content-card">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-briefcase"></i>
            Dernières offres publiées
        </h2>
        <a href="{{ route('offres-emplois.index') }}" class="btn-secondary" style="background:#f5f7fa; border:1px solid #eceff3;">
            <i class="fas fa-list"></i>
            Voir toutes
        </a>
    </div>

    <div class="candidates-list" style="gap: 10px;">
        @forelse(($offres ?? collect()) as $offre)
            <div class="candidate-item" style="grid-template-columns: 40px 1fr auto;">
                <div class="candidate-avatar" style="width:40px;height:40px;">
                    <i class="fas fa-briefcase" style="font-size:14px;color:var(--accent);"></i>
                </div>
                <div class="candidate-info">
                    <h4 class="candidate-name" style="margin-bottom:2px;">{{ $offre->titre }}</h4>
                    <p class="candidate-skills" style="gap:6px;">
                        <i class="fas fa-map-marker-alt" style="font-size:12px;color:var(--gray);"></i> <span style="color:var(--primary);font-weight:600;">{{ $offre->ville_travail }}</span>
                        @if($offre->date_expiration)
                            <span class="badge badge-secondary">Expire {{ optional($offre->date_expiration)->diffForHumans() }}</span>
                        @endif
                        @if($offre->offre_urgente)
                            <span class="badge badge-primary">Urgent</span>
                        @endif
                    </p>
                </div>
                <div class="candidate-actions">
                    <a class="btn-sm btn-view" href="{{ route('offres-emplois.show', $offre->offre_id) }}"><i class="fas fa-eye"></i></a>
                    <a class="btn-sm" style="background:#f5f7fa;" href="{{ route('offres-emplois.edit', $offre->offre_id) }}"><i class="fas fa-edit"></i></a>
                </div>
            </div>
        @empty
            <div class="candidate-item" style="justify-content:center;">
                <div class="candidate-info" style="text-align:center;">
                    <h4 class="candidate-name">Aucune offre</h4>
                    <p class="candidate-skills"><i class="fas fa-info-circle"></i> Publiez votre première offre pour attirer des talents.</p>
                </div>
            </div>
        @endforelse
    </div>

    @if(isset($offres) && $offres instanceof \Illuminate\Contracts\Pagination\Paginator)
        <div style="display:flex; justify-content:flex-end; margin-top:10px;">
            {!! $offres->withQueryString()->links() !!}
        </div>
    @endif
</div>


