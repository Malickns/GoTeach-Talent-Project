<div class="content-card">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-star"></i>
            Offres Recommandées pour Vous
        </h2>
        <a href="{{ route('jeunes.offres') }}" class="btn-primary">
            <i class="fas fa-search"></i>
            Voir toutes les offres
        </a>
    </div>
    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
        @forelse($offresRecommandees as $offre)
        @if($loop->index < 6)
        <div class="offre-card" style="margin-bottom: 0; padding: 16px;">
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
                    <i class="fas fa-industry"></i>
                    <span>{{ $offre->employeur->secteur_activite ?? 'Secteur' }}</span>
                </div>
                <div class="offre-detail">
                    <i class="fas fa-clock"></i>
                    <span>{{ $offre->created_at ? $offre->created_at->diffForHumans() : 'Date inconnue' }}</span>
                </div>
            </div>
            <p class="offre-description" style="-webkit-line-clamp: 2; font-size:13px;">{{ Str::limit($offre->description, 140) }}</p>
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
        <div class="offre-card" style="text-align: center; padding: 40px;">
            <i class="fas fa-search" style="font-size: 48px; color: var(--gray); margin-bottom: 20px;"></i>
            <h3 style="color: var(--gray); margin-bottom: 10px;">Aucune offre recommandée</h3>
            <p style="color: var(--gray);">Complétez vos préférences pour des recommandations plus précises.</p>
            <a href="{{ route('profile.edit') }}" class="btn-primary" style="margin-top: 20px;">
                <i class="fas fa-user-edit"></i>
                Compléter mes préférences
            </a>
        </div>
        @endforelse
    </div>
    @if(method_exists($offresRecommandees, 'hasPages') && $offresRecommandees->hasPages())
    <div style="display:flex; justify-content:center; align-items:center; gap:8px; margin-top:18px; flex-wrap:wrap;">
        @if($offresRecommandees->onFirstPage())
            <span style="padding:8px 12px; border:1px solid #ddd; border-radius:6px; color:#aaa;">Précédent</span>
        @else
            <a href="{{ $offresRecommandees->previousPageUrl() }}" style="padding:8px 12px; border:1px solid #ddd; border-radius:6px; text-decoration:none; color:#333;">Précédent</a>
        @endif

        @for ($page = 1; $page <= $offresRecommandees->lastPage(); $page++)
            @if ($page == $offresRecommandees->currentPage())
                <span style="padding:8px 12px; border:1px solid var(--primary); background: linear-gradient(90deg, var(--primary), var(--accent)); color:#fff; border-radius:6px;">{{ $page }}</span>
            @else
                <a href="{{ $offresRecommandees->url($page) }}" style="padding:8px 12px; border:1px solid #ddd; border-radius:6px; text-decoration:none; color:#333;">{{ $page }}</a>
            @endif
        @endfor

        @if($offresRecommandees->hasMorePages())
            <a href="{{ $offresRecommandees->nextPageUrl() }}" style="padding:8px 12px; border:1px solid #ddd; border-radius:6px; text-decoration:none; color:#333;">Suivant</a>
        @else
            <span style="padding:8px 12px; border:1px solid #ddd; border-radius:6px; color:#aaa;">Suivant</span>
        @endif
    </div>
    @endif
</div>


