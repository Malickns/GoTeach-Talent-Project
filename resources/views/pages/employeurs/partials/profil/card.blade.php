<div class="sidebar-card profile-card">
    <div class="company-logo">
        <i class="fas fa-building"></i>
    </div>
    <h3 class="company-name">{{ Auth::user()->prenom ?? 'Mon Entreprise' }} {{ Auth::user()->nom ?? '' }}</h3>
    <p class="company-type">Partenaire Premium</p>
    <ul class="stats-list">
        <li><span>Offres publiées</span><span class="stats-value">{{ $stats['offres_publiees'] ?? 0 }}</span></li>
        <li><span>Candidatures</span><span class="stats-value">{{ $stats['candidatures_recues'] ?? 0 }}</span></li>
        <li><span>Talents contactés</span><span class="stats-value">{{ $stats['talents_contactes'] ?? 0 }}</span></li>
        <li><span>Entretiens</span><span class="stats-value">{{ $stats['entretiens'] ?? 0 }}</span></li>
    </ul>
    
</div>


