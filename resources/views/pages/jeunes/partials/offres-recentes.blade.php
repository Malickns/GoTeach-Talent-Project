<div class="content-card">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-clock"></i>
            Offres Récentes
        </h2>
        <a href="{{ route('jeunes.offres') }}" class="btn-secondary">
            <i class="fas fa-list"></i>
            Voir tout
        </a>
    </div>
    <div class="offres-grid" style="grid-template-columns: repeat(3, minmax(0,1fr)); gap:16px;">
        @forelse($offresRecentes as $offre)
        @if($loop->index < 6)
        <div class="offre-card" style="padding:16px;">
            <div class="offre-header">
                <div>
                    <h3 class="offre-title" style="font-size:16px;">{{ $offre->titre }}</h3>
                    <p class="offre-company" style="font-size:12px;">{{ $offre->employeur->nom_entreprise ?? 'Entreprise' }}</p>
                </div>
                <span class="offre-badge">{{ $offre->type_contrat }}</span>
            </div>
            <div class="offre-details">
                <div class="offre-detail">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $offre->ville_travail }}</span>
                </div>
                <div class="offre-detail">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>{{ $offre->grade ?? 'À négocier' }}</span>
                </div>
                <div class="offre-detail">
                    <i class="fas fa-clock"></i>
                    <span>{{ $offre->created_at ? $offre->created_at->diffForHumans() : 'Date inconnue' }}</span>
                </div>
            </div>
            <p class="offre-description" style="-webkit-line-clamp:2; font-size:13px;">{{ Str::limit($offre->description, 120) }}</p>
            <div class="offre-actions">
                <a href="{{ route('jeunes.offres.show', $offre) }}" class="btn-secondary">
                    <i class="fas fa-eye"></i>
                    Voir détails
                </a>
                @if(!$jeune->hasPostule($offre->offre_id))
                <a href="{{ route('jeunes.offres.candidature', $offre) }}" class="btn-success">
                    <i class="fas fa-paper-plane"></i>
                    Postuler
                </a>
                @else
                <span class="btn-warning">
                    <i class="fas fa-check"></i>
                    Déjà postulé
                </span>
                @endif
            </div>
        </div>
        @endif
        @empty
        <div class="offre-card" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
            <i class="fas fa-briefcase" style="font-size: 48px; color: var(--gray); margin-bottom: 20px;"></i>
            <h3 style="color: var(--gray); margin-bottom: 10px;">Aucune offre disponible</h3>
            <p style="color: var(--gray);">Aucune offre d'emploi n'est actuellement disponible.</p>
        </div>
        @endforelse
    </div>
</div>


