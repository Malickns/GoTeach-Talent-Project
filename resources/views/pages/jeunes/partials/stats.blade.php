<div class="content-card">
    <div class="card-header">
        <h2 class="card-title">
            <i class="fas fa-chart-line"></i>
            Mon Activité
        </h2>
        <a href="{{ route('jeunes.candidatures') }}" class="btn-secondary">
            <i class="fas fa-eye"></i>
            Voir tout
        </a>
    </div>
    <div class="stats-grid">
        <div class="stat-item">
            <span class="stat-number">{{ $statistiques['total_candidatures'] }}</span>
            <div class="stat-label">Candidatures</div>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $statistiques['entretiens'] }}</span>
            <div class="stat-label">Entretiens</div>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $statistiques['emplois_obtenus'] }}</span>
            <div class="stat-label">Emplois Obtenus</div>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $statistiques['offres_disponibles'] }}</span>
            <div class="stat-label">Offres Disponibles</div>
        </div>
    </div>
</div>


