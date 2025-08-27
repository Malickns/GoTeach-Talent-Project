<div class="content-card">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-chart-line"></i>
            Aperçu des Performances
        </h2>
        <a href="#" class="btn-secondary" style="background:#f5f7fa; border:1px solid #eceff3;">
            <i class="fas fa-chart-bar"></i>
            Voir plus
        </a>
    </div>
    <div class="stats-grid">
        <div class="stat-item">
            <span class="stat-number">{{ $stats['vues_profil'] }}</span>
            <div class="stat-label">Vues Profil</div>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $stats['candidatures_recues'] }}</span>
            <div class="stat-label">Candidatures</div>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $stats['offres_publiees'] }}</span>
            <div class="stat-label">Offres Publiées</div>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $stats['entretiens'] }}</span>
            <div class="stat-label">Entretiens</div>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $stats['taux_reponse'] }}%</span>
            <div class="stat-label">Taux de Réponse</div>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $stats['embauches'] }}</span>
            <div class="stat-label">Embauches</div>
        </div>
    </div>
</div>


